import React from "react";

function Search(props) {
    // On retient dans la state une liste des espèces disponibles et un array permettant de retrouver l'id via le nom de l'espèce
    const [especes, setEspeces] = React.useState([]);
    const [idOf, setIdOf] = React.useState([]);

    // On utilise l'API pour récupérer la liste des espèces puis on la stocke dans la state
    React.useEffect(() => {
        fetch("http://192.168.56.108/api/espece/")
            .then(response => response.json())
            .then(data => {
                setEspeces(data.map(espece => espece[0].espece));
                let idList = [];
                for (let espece of data) idList[espece[0].espece] = espece[0].id;
                setIdOf(idList);
            });
    }, []);

    // Le formulaire est contruit avec les valeurs de la dernière recherche en placeholders
    // La liste des espèce remplis une datalist permettant l'autocomplete du champ "espèce"
    // Un champ caché permet de transmettre l'id de l'espèce alors que l'utilisateur n'en verra que le nom
    // L'action du formulaire est celle par défaut : recharger la page avec une querystring correspondant au formulaire
    return (
        <form>
            <div>
                <label htmlFor="espece">Espèce</label>
                <input required type="text" list="especeList" id="espece" name="espece" placeholder={props.basevalue[0]} onChange={() => {document.querySelector("#especeValue").value = idOf[document.querySelector("#espece").value]}} />
                <input type="hidden" id="especeValue" name="especeValue" defaultValue={undefined} />
                <datalist id="especeList">
                    {especes.map(espece => <option key={espece} value={espece} />)}
                </datalist>
            </div>
            <div>
                <label htmlFor="debut">Début</label>
                <input required type="text" id="debut" name="debut" placeholder={props.basevalue[2]} />
            </div>
            <div>
                <label htmlFor="fin">Fin</label>
                <input required type="text" id="fin" name="fin" placeholder={props.basevalue[3]} />
            </div>

            <div>
                <input type="submit" value="Rechercher" />
            </div>
        </form>
    );
}

export default Search;
