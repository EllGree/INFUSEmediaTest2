const app = {
    call: async (method) => { // possible methods: 'dice', 'log', 'view'
        const uri = "main.php?method=" + method + "&id=" + app.currentId;
        const response = await fetch(uri);
        const data = await response.text();
        return data;
    },
    refresh: () => app.call('view').then(app.showCounter),
    imageFound: () => {
        if(app.stop === 0) {
            app.call('log').then(app.showCounter);
            setInterval(app.refresh, 5000);
        }
    },
    imageSet: (id) => {
        app.currentId = parseInt(id);
        app.img.src = './src/' + app.currentId.toString() + '.jpg';
    },
    showCounter: (counter) => {
        app.msg.innerText = parseInt(counter).toString();
    },
    init: () => {
        app.stop = app.currentId = 0;
        app.img = document.querySelector('img');
        app.msg = document.getElementById('value');
        app.img.onload = app.imageFound;
        app.img.onerror = () => {
            app.stop = 1;
            app.img.src = './src/empty.jpg';
        };
        app.call('dice').then(app.imageSet);
    }
};
window.addEventListener("DOMContentLoaded", app.init);
