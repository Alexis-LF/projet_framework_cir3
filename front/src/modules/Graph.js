import React from "react";

// La bibliothèque d3 ainsi que les fonctions Legend et GroupedBarChart de observablehq.com sont
// ici utilisés pour créer le graphe à partir des données. 
import * as d3 from "d3";
import GroupedBarChart from "./GroupedBarChart";
import Legend from "./Legend";

function Graph(props) {
    // On utilise la state pour enregistrer les zones disponibles et les données de la recherche
    const [data, setData] = React.useState([]);
    const [zones, setZones] = React.useState([]);

    // On charge ici les données à partir de l'API (et on les restructure légèrement pour les fonctions de graphe)
    React.useEffect(() => {
        fetch("http://192.168.56.108/api/zone/")
            .then(response => response.json())
            .then(data => { setZones(data.map(zone => zone[0].zone)) });

        fetch(`http://192.168.56.108/api/echouages/espece/${props.search[1]}/`)
            .then(response => response.json())
            .then(data => {
                setData(
                    // Le bloc ci-dessous restructure les données pour ne garder que celles comprises entre les dates de la recherche
                    // Il rassemble aussi les données en un décompte des échouages par année et par zone
                    data.reduce((acc, val) => {
                        if (val.date < props.search[2] || val.date > props.search[3]) return acc;
                        for (let i in acc) {
                            if (acc[i].date === val.date && acc[i].zone === val.zone) {
                                acc[i].amount += parseInt(val.nombre);
                                return acc;
                            }
                        }
                        acc.push({ date: val.date, zone: val.zone, amount: parseInt(val.nombre) });
                        return acc;
                    }, [])
                )
            });
    }, [props.search]);

    // On fait le rendu du graphe ici, un problème d'insertion de l'élément svg qui constitue le graphe
    // a forcé l'utilisation de appendChild plutôt que la méthode habituelle de retour de JSX.
    React.useEffect(() => {
        // On nettoie la zone d'affichage et on vérifie que les données nécessaire au rendu du graphe soient disponibles.
        while (document.querySelector("#graph").children.length) document.querySelector("#graph").children[0].remove();
        if (!data.length || !zones.length || !props.search.length) return;

        // On génère le graphe
        let chart = GroupedBarChart(data, {
            x: d => d.date,
            y: d => d.amount,
            z: d => d.zone,
            //xDomain: d3.groupSort(data, D => d3.sum(D, d => -d.amount), d => d.date),
            yLabel: `↑ Nombre d'échouages de ${props.search[1] !== "undefined" ? props.search[0] : "Baleine de Cuvier"} par zone et par année`,
            zDomain: zones,
            colors: d3.schemeSpectral[zones.length],
            width: Math.round(window.screenX * 0.75),
            height: Math.round(window.screenY * 0.5)
        });

        // On génère la légende
        let legend = Legend(chart.scales.color, {title: "Zones", width: Math.round(window.screenX * 0.75)});

        // On ajoute le tout au document
        document.querySelector("#graph").appendChild(chart);
        document.querySelector("#graph").appendChild(legend);
    }, [data, zones, props.search]);

    // On signifie à react que ce composant ne retourne pas d'élément en explicitant "null"
    return null;
}

export default Graph;
