function liveUhr() {
    const now = new Date();
    const formatted = now.toLocaleString('de-DE', {
        day: '2-digit', month: '2-digit', year: 'numeric',
        hour: '2-digit', minute: '2-digit', second: '2-digit'
    });
    document.getElementById('liveClock').innerText = formatted;
}
setInterval(liveUhr, 1000);
liveUhr();
