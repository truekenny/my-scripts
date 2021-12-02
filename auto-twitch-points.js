function getButtonWithAttribute(name, value)
{
    var allElements = document.getElementsByTagName('button');
    for (var i = 0, n = allElements.length; i < n; i++) {
        if (allElements[i].getAttribute(name) === value) {
            return allElements[i];
        }
    }
    return null;
}

function check111() {
    // let button = getButtonWithAttribute('aria-label', 'Количество баллов');
    let button = getButtonWithAttribute('aria-label', 'Получить бонус');

    if (button) {
        console.log('check111: click');
        button.click();

        let au = new Audio('http://tempic.ru/s.mp3');
		au.volume = 0.1;
		au.play();
    }
    else {
        console.log('check111: 404');
    }

    setTimeout('check111()', 5000);
}

setTimeout('check111()', 1000);
