/* let btnMas = document.getElementById("btnMas");
let btnMenos = document.getElementById("btnMenos"); */ 
let barraProgreso = document.getElementById("barraProgreso"); 
let btnInsertar = document.getElementById("btnInsertar"); 
let inputPorcentaje = document.getElementById("inputPorcentaje");
let numPorcentaje = document.getElementById("numPorcentaje");

/* let valor = barraProgreso.value; */

console.log(numPorcentaje.textContent);

/* function subirValor(){
    valor += 1;
    barraProgreso.setAttribute('value', valor);
}

function disminuirValor(){
    valor -= 1;
    barraProgreso.setAttribute('value', valor);
} */

function cambiarValor(){
    numPorcentaje.textContent = inputPorcentaje.valueAsNumber;
    barraProgreso.setAttribute('value', inputPorcentaje.valueAsNumber);
}

/* btnMas.addEventListener("click", subirValor);
btnMenos.addEventListener("click", disminuirValor); */
btnInsertar.addEventListener("click", cambiarValor);