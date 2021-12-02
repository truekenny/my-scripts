// Type your JavaScript code here.

let globalV = 'test';

function check() {
    let className = 'des_check';

    let url = new URL(document.location.href);
    let v = url.searchParams.get("v");

    if(v && globalV != v) {
        globalV = v;
        console.log({video: v});

        const xhttp = new XMLHttpRequest();
        xhttp.onload = function() {
            let data = JSON.parse(this.responseText)
            console.log({resultJson: data});

            let dislikeCount = data.items.length 
                ? data.items[0].statistics.dislikeCount
                : '-1';
            console.log({dislikeCount: dislikeCount});

            let timer100, timer1000, timer2000, timer5000;
            let f = function() {
                document.querySelectorAll('yt-formatted-string, '
                    + ' .' + className)
                .forEach((el) => {
                    if(el.innerText == 'НЕ НРАВИТСЯ'
                    || el.classList.contains(className)) {
                        el.classList.add(className);
                        console.log('НЕ НРАВИТСЯ - found');
                        el.innerText = dislikeCount;

                        clearTimeout(timer100);
                        clearTimeout(timer1000);
                        clearTimeout(timer2000);
                        clearTimeout(timer5000);
                    }
                });
            };
            timer100 = setTimeout(f, 100);
            timer1000 = setTimeout(f, 1000);
            timer2000 = setTimeout(f, 2000);
            timer5000 = setTimeout(f, 5000);
        }
        xhttp.open("GET", "https://www.googleapis.com/youtube/v3/videos?key=AIzaSyCxz7bmUrrK8zNLMUoJ_YOSWqicXaDHMlc&part=statistics&id=" + v);
        xhttp.send();
    }
    else {
        console.log('check: none');
    }

    setTimeout('check()', 1000);    
}

setTimeout('check()', 1000);
