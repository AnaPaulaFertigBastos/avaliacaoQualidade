document.addEventListener('DOMContentLoaded', function () {
  const link = document.querySelector('.actions a.btn.submit-btn');
  if (link && link.href) {
    setTimeout(function () {
      window.location.href = link.href;
    }, 5000);
  }
});
