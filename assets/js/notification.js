(() => {
    const notification = document.querySelector('.notification');
    if (!notification) return;
    const close = () => {
        notification.classList.add('notification-leaving');
        setTimeout(() => notification.remove(), 250);
    };
    const timer = setTimeout(close, 6000);
    notification.querySelector('button').addEventListener('click', () => {
        clearTimeout(timer);
        close();
    });
})();
