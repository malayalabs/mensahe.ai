// Background service worker for the Chrome extension

// Handle extension installation
chrome.runtime.onInstalled.addListener(() => {
  // Runs when the extension is installed
});

// This is the core logic for the extension's toggle functionality.
chrome.runtime.onMessage.addListener((message, sender, sendResponse) => {
  if (message.action === 'toggleWidget') {
    chrome.tabs.query({ active: true, currentWindow: true }, (tabs) => {
      const activeTab = tabs[0];
      if (activeTab && activeTab.id) {
        // Just send the message. The content script is already on the page.
        chrome.tabs.sendMessage(activeTab.id, { action: 'toggleMensaheWidget' });
      }
    });
  }
  return true; // Keep channel open for async response
});

// Handle tab updates
chrome.tabs.onUpdated.addListener(function(tabId, changeInfo, tab) {
  if (changeInfo.status === 'complete' && tab.url) {
    // Check if widget should be active on this page
    chrome.storage.local.get(['widgetActive'], function(result) {
      if (result.widgetActive) {
        // Widget is active, ensure it's visible on the new page
        setTimeout(() => {
          chrome.tabs.sendMessage(tabId, { action: 'initWidget' });
        }, 1000);
      }
    });
  }
}); 