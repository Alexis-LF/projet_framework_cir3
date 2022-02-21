import './App.css';

import React from 'react';

import Title from './modules/Title';
import Graph from './modules/Graph';
import Subtitle from './modules/Subtitle';
import Search from './modules/Search';

function App() {
  // Cette variable retient la dernière recherche effectuée au format [nom_espece, id_espece, date_debut, date_fin]
  const [search, changeSearch] = React.useState(["Baleine de Cuvier", "1", "1994", "2015"]);

  // Ici, on récupère les paramètres GET de la recherche pour les stocker dans la state 
  React.useEffect(() => {
    let params = new URLSearchParams(window.location.search);
    let espece = params.get("espece") || search[0];
    let especeValue = params.get("especeValue") || search[1];
    let debut = params.get("debut") || search[2];
    let fin = params.get("fin") || search[3];

    changeSearch([
      espece,
      especeValue,
      debut,
      fin,
    ]);
  }, []);

  return (
    <div className="App">
      <header>
        <Title text="Echouages" />
        <Search basevalue={search} callback={changeSearch}/>
      </header>

      <main id="graph">
        <Graph search={search} />
      </main>

      <footer>
        <Subtitle text="Alexis LE FLOCH et Noam NEDELEC-SALMON" />
      </footer>
    </div>
  );
}

export default App;
