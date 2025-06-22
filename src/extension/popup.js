document.addEventListener('DOMContentLoaded', function() {
  const openChatBtn = document.getElementById('open-chat');
  const toggleWidgetBtn = document.getElementById('toggle-widget');

  openChatBtn.addEventListener('click', function() {
    chrome.tabs.create({ url: 'http://localhost:3000' });
  });

  toggleWidgetBtn.addEventListener('click', function() {
    chrome.runtime.sendMessage({ action: 'toggleWidget' });
  });
}); 