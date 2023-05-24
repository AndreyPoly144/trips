//ОБЪЯВЛЕНИЕ ПЕРЕМЕННЫХ
let items = document.querySelectorAll('.item');
let hideBar = document.querySelector('.hidebar');
let hbtext = document.querySelector('.hbtext');
let btnLoad = document.querySelector('.button');
let hideInput = document.querySelector("[hidden=\'hidden\']");
let form = document.querySelector('form');
let itemsBlock = document.querySelector('.items');
let text = document.querySelector('.text');
let curPathText = document.querySelector('.curpathtext');
let back = document.querySelector('.back');
let dirPath = 'disk:/';
let path;

//ФУНКЦИИ ОБРАБОТЧИКИ

//при нажатии пкм по item
openHideBar = (e) => {
    if (e.target.dataset.item == 'true') {         //в dataset лежат все нестандартные атриубты, если там есть item со значением true значит этот элемент item
        e.preventDefault();
        hideBar.classList.remove('hidden');
        hideBar.style.left = e.pageX + 3 + 'px';
        hideBar.style.top = e.pageY + 3 + 'px';
        if (e.target.dataset.pathtoitem) {
            path = e.target.dataset.pathtoitem       // путь до item в янд диске
        } else {
            let parent = e.target.closest('.item');
            path = parent.dataset.pathtoitem;
        }
    }
}

function closeHideBar(e) {
    hideBar.classList.add('hidden');
}

//при нажатии удалить
function clickDelete(e) {
    text.textContent = ' ';
    let deleteItem = document.querySelector(`[data-pathtoitem="${path}"]`);
    let data = new FormData();
    data.append('data-pathtoitem', path);

    let xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (xhttp.readyState == 4 && xhttp.status == 200) {
            deleteItem.remove();
        }
    }
    xhttp.open('POST', 'delete.php', true);
    xhttp.responseType = 'json';  //в xhttp св-во responseType в брузере должно стать 'json' иначе js не обработает данные переданые из php файла
    xhttp.send(data);           //принимает строку и еще несколько типов (Document, Blob, FormData)
}

//срабатывает когда нажали на кнопку загрузить
function clickLoad() {
    hideInput.value = '';
    hideInput.click();
    text.textContent = ' ';     //текст обнуляется при нажатии на загрузить

}

//срабатывает когда выбрали файл (или сменили выбор файла, например выбран был один а выбралии другой)
function loadFile(e) {
    let file = hideInput.files['0'];
    const data = new FormData();
    data.append("thefile", file);
    let xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (xhttp.readyState == 4 && xhttp.status == 200) {
            if (xhttp.response.result === true) {         //если файл успешно загружен, выведем его
                itemsBlock.insertAdjacentHTML('beforeend', `
                    <div class="item" data-pathtoitem="${xhttp.response.curpath}"  data-item="true"><img src="images/file1.png" alt="file" class="filepic"   data-item="true" style="width: 75px;"><p  data-item="true">
                    ${xhttp.response.fileName}</p></div>`
                )

            } else if (xhttp.response.result === 'error') {
                text.textContent = xhttp.response.text;
                text.classList.add('error');
            }
        }
    }
    xhttp.open('POST', 'upload.php', true);
    xhttp.responseType = 'json';
    xhttp.send(data);
}

//при клике по папке
function clickToDir(e) {
    if (e.target.dataset.dir == 'true') {              //отбираем только папки
        if (e.target.dataset.pathtoitem) {
            dirPath = e.target.dataset.pathtoitem       // путь до папки в янд диске
        } else {
            let parent = e.target.closest('.item');
            dirPath = parent.dataset.pathtoitem;
        }

        const data = new FormData();
        data.append('dirPath', dirPath);
        let xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                if (xhttp.response.newpath) {
                    curPathText.textContent = xhttp.response.newpath;
                    dirPath = xhttp.response.newpath;
                    if (dirPath !== 'disk:/' && back.classList.contains('hidden')) {
                        back.classList.remove('hidden')
                    }
                }
                itemsBlock.replaceChildren();            //удаляем все дочерние элементы
                itemsBlock.insertAdjacentHTML('beforeend', xhttp.response.output)
            }
        }
        xhttp.open('POST', 'clickdir.php', true);
        xhttp.responseType = 'json';
        xhttp.send(data);
    }
}

//при клике назад
function clickToBack(e) {
    const data = new FormData();
    data.append('dirPath', dirPath);
    let xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (xhttp.readyState == 4 && xhttp.status == 200) {
            if (xhttp.response.newpath) {
                curPathText.textContent = xhttp.response.newpath;
                dirPath = xhttp.response.newpath;
                if (dirPath === 'disk:/' && !back.classList.contains('hidden')) {
                    back.classList.add('hidden')
                }
            }
            itemsBlock.replaceChildren();            //удаляем все дочерние элементы
            itemsBlock.insertAdjacentHTML('beforeend', xhttp.response.output)
        }
    }
    xhttp.open('POST', 'clickback.php', true);
    xhttp.responseType = 'json';
    xhttp.send(data);

}

//ВЕШАЕМ ОБРАБОТЧИКИ
itemsBlock.addEventListener('contextmenu', openHideBar);
itemsBlock.addEventListener('click', clickToDir);
back.addEventListener('click', clickToBack);
document.documentElement.addEventListener('click', closeHideBar);
document.documentElement.addEventListener('contextmenu', closeHideBar, true);
hbtext.addEventListener('click', clickDelete);
btnLoad.addEventListener('click', clickLoad);
hideInput.addEventListener('change', loadFile);



