const data = [{fullname: "Juan",desc: "Hola buenas tardes",serial: "368347863",status: "on",prio: "High"},
    {fullname: "Juan",desc: "Hola buenas tardes",serial: "368347863",status: "on",prio: "High"},
    {fullname: "Miguel",desc: "Hola buenas tardes",serial: "368347863",status: "on",prio: "High"},
    {fullname: "Jose",desc: "Adios buenas tardes",serial: "368347863",status: "off",prio: "Medium"},
    {fullname: "Victor",desc: "Hola buenas tardes", serial: "59749663856",status: "on",prio: "High"},
    {fullname: "Alejandro",desc: "Hola buenas tardes",serial: "574697450",status: "off",prio: "Low"}];

const tbody = document.querySelector('#body');
const input = document.querySelector('#filter');

window.onload = populateTable();

input.onkeyup = function (event) {
    const val = input.value.toLowerCase();
    if (val.length >= 3) {
        const rows = Array.from(tbody.querySelectorAll('tr'));
        rows.filter(function (row) {
            const fullname = row.cells[1].textContent.toLowerCase();
            const desc = row.cells[2].textContent.toLowerCase();
            if (fullname.includes(val) || desc.includes(val)) {
                row.style.color = "green";
            }
        });
    } else {
        tbody.querySelectorAll('tr').forEach(function (row) {
            row.style.color = "";
        });
    }
}

function populateTable() {
    data.forEach(item => {
        const rows = document.createElement('tr');
        const btn = document.createElement('button');
        const action = document.createElement('td');
        btn.value = 'borrar';
        btn.id = 'btn';
        btn.innerText = 'X';
        action.appendChild(btn);
        Object.values(item).forEach(value => {
            const td = document.createElement('td');
            td.appendChild(document.createTextNode(value));
            rows.insertAdjacentElement("afterbegin", action);
            rows.appendChild(td);
        });
        btn.onclick = () => rows.remove(); 
        tbody.appendChild(rows);
    });
}