import React from "react";

function NavForm(props) {
    return (
        <form>
            <div>
                <label htmlFor="espece">Espece</label>
                <input type="text" id="espece" name="espece" placeholder="Espece" />
            </div>
            <div>
                <label htmlFor="zone">Zone</label>
                <input type="text" id="zone" name="zone" placeholder="Zone" />
            </div>
            <div>
                <label htmlFor="debut">Debut</label>
                <input type="text" id="debut" name="debut" placeholder="Debut" />
            </div>
            <div>
                <label htmlFor="fin">Fin</label>
                <input type="text" id="fin" name="fin" placeholder="Fin" />
            </div>
        </form>
    );
}

export default NavForm;
