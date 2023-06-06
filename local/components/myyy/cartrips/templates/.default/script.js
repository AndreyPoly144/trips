window.onload = function () {
    let btn = document.querySelector('.btnshow');
    let form = document.querySelector('form');
    let start = document.querySelector('.start');
    let end = document.querySelector('.end');
    let err = document.querySelector('.err');
    let block = document.querySelector('.tripsblock');

    function btnHandler(e) {
        e.preventDefault();
        err.textContent = '';
        block.replaceChildren();

        let xhttp = new XMLHttpRequest();
        let params = 'start=' + encodeURIComponent(start.value) +   //сами составляем get запрос и кодируем его
            '&end=' + encodeURIComponent(end.value) + '&car=list';
        console.dir(start);
        xhttp.open('GET', '/local/components/myyy/cartrips/templates/.default/handling/time.php' + '?' + params, true);
        xhttp.responseType = 'json';
        xhttp.send();

        xhttp.onreadystatechange = function () {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                console.dir(xhttp);
                if (xhttp.response.status == 'error') {
                    err.textContent = xhttp.response.message;
                } else if (xhttp.response.status == 'success') {
                    block.insertAdjacentHTML('beforeend', xhttp.response.carlist)
                }
            }
        }
    }

    function carHandler(e) {
        if (e.target.className == 'car') {
            err.textContent = '';
            const data = new FormData();
            data.append("carid", e.target.dataset.carid);
            data.append("start", start.value);
            data.append("end", end.value);
            let xhttp = new XMLHttpRequest();

            xhttp.onreadystatechange = function () {
                if (xhttp.readyState == 4 && xhttp.status == 200) {
                    block.replaceChildren();
                    if (xhttp.response.status == 'error') {
                        err.textContent = xhttp.response.message;
                    }
                    if (xhttp.response.status == 'success') {
                        err.textContent = xhttp.response.message;
                    }
                }
            }

            xhttp.open('POST', '/local/components/myyy/cartrips/templates/.default/handling/car.php', true);
            xhttp.responseType = 'json';
            xhttp.send(data);
        }
    }

    btn.addEventListener('click', btnHandler);
    document.documentElement.addEventListener('click', carHandler)
}