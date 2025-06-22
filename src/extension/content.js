const WIDGET_CONTAINER_ID = 'mensahe-widget-container';
const LAUNCHER_ID = 'mensahe-launcher';

// Backend configuration
const BACKEND_URL = 'http://localhost:8080';

function getLoginHTML(iconUrl) {
  return `
    <div class="title-bar">
      <button class="close-button" title="Close">
        &times;
      </button>
    </div>
    <div class="login-wrapper">
      <div class="logo-container">
        <img src="${iconUrl}" alt="Mensahe Logo" class="logo">
        <h1>Mensahe</h1>
      </div>
      <p>A secure peer-to-peer messaging system.</p>
      <div class="input-group">
        <label for="username">Enter your name</label>
        <input type="text" id="username" name="username" placeholder="e.g., Jane Doe" />
      </div>
      <button id="login-button">Sign In</button>
      <div id="status-message" class="status-message"></div>
      <div class="footer">
        <p>Made with ❤️ from 🇵🇭</p>
      </div>
    </div>
  `;
}

async function handleLogin(username, shadowRoot) {
  const statusElement = shadowRoot.getElementById('status-message');

  try {
    statusElement.textContent = 'Connecting to server...';
    statusElement.className = 'status-message loading';

    // Step 1: Get registration options from server
    const response = await fetch(`${BACKEND_URL}/registerRequest.php`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ username })
    });

    const data = await response.json();

    if (!response.ok) {
      throw new Error(data.error || 'Server error');
    }

    statusElement.textContent = 'Creating passkey...';
    statusElement.className = 'status-message loading';

    // Step 2: Create WebAuthn credential
    const credential = await createWebAuthnCredential(data, username);

    statusElement.textContent = 'Verifying passkey...';
    statusElement.className = 'status-message loading';

    // Step 3: Send credential to server for verification
    const verifyResponse = await fetch(`${BACKEND_URL}/registerVerify.php`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        username,
        credential: {
          id: credential.id,
          type: credential.type,
          rawId: arrayBufferToBase64(credential.rawId),
          response: {
            clientDataJSON: arrayBufferToBase64(credential.response.clientDataJSON),
            attestationObject: arrayBufferToBase64(credential.response.attestationObject),
          }
        }
      })
    });

    const verifyData = await verifyResponse.json();

    if (verifyResponse.ok) {
      statusElement.textContent = '✅ Passkey created successfully! You can now sign in securely.';
      statusElement.className = 'status-message success';

      // Store user session or redirect to chat
      setTimeout(() => {
        hideWidget();
        // TODO: Open chat interface or show success message
      }, 3000);

    } else {
      throw new Error(verifyData.error || 'Verification failed');
    }

  } catch (error) {
    statusElement.textContent = `❌ Error: ${error.message}`;
    statusElement.className = 'status-message error';
    console.error('Login error:', error);
  }
}

async function createWebAuthnCredential(options, username) {
  // Convert base64 strings back to ArrayBuffer for WebAuthn API
  const publicKeyOptions = {
    challenge: base64ToArrayBuffer(options.challenge),
    rp: options.rp,
    user: {
      ...options.user,
      id: base64ToArrayBuffer(options.user.id)
    },
    pubKeyCredParams: options.pubKeyCredParams || [
      { alg: -7, type: 'public-key' }, // ES256
      { alg: -257, type: 'public-key' } // RS256
    ],
    timeout: options.timeout || 60000,
    attestation: options.attestation || 'none',
    authenticatorSelection: options.authenticatorSelection || {
      authenticatorAttachment: 'platform',
      userVerification: 'preferred'
    }
  };

  try {
    const credential = await navigator.credentials.create({
      publicKey: publicKeyOptions
    });

    return credential;
  } catch (error) {
    console.error('WebAuthn creation error:', error);
    
    // Provide user-friendly error messages
    if (error.name === 'NotAllowedError') {
      throw new Error('Passkey creation was cancelled or not supported by your device');
    } else if (error.name === 'InvalidStateError') {
      throw new Error('A passkey already exists for this account');
    } else if (error.name === 'NotSupportedError') {
      throw new Error('Your browser or device does not support passkeys');
    } else {
      throw new Error(`Passkey creation failed: ${error.message}`);
    }
  }
}

// Utility functions for base64 conversion
function arrayBufferToBase64(buffer) {
  const bytes = new Uint8Array(buffer);
  let binary = '';
  for (let i = 0; i < bytes.byteLength; i++) {
    binary += String.fromCharCode(bytes[i]);
  }
  return btoa(binary);
}

function base64ToArrayBuffer(base64) {
  const binaryString = atob(base64);
  const bytes = new Uint8Array(binaryString.length);
  for (let i = 0; i < binaryString.length; i++) {
    bytes[i] = binaryString.charCodeAt(i);
  }
  return bytes.buffer;
}

function initializeMensaheWidget() {
  // --- Create Elements ---
  const widgetContainer = document.createElement('div');
  widgetContainer.id = WIDGET_CONTAINER_ID;
  document.body.appendChild(widgetContainer);

  const launcher = document.createElement('button');
  launcher.id = LAUNCHER_ID;

  // --- Get Icon URL ---
  const iconUrl = chrome.runtime.getURL('assets/mensahe-logo.svg');

  // --- Configure Launcher ---
  launcher.innerHTML = `<img src="${iconUrl}" alt="Mensahe">`;
  launcher.style.display = 'flex'; // Show launcher by default
  document.body.appendChild(launcher);

  // --- Configure Widget ---
  Object.assign(widgetContainer.style, {
    position: 'fixed',
    bottom: '20px',
    right: '20px',
    width: '350px',
    height: '500px',
    zIndex: '9999',
    display: 'none', // Start hidden
    borderRadius: '3px',
    overflow: 'hidden',
    border: '1px solid #000000',
    boxShadow: '5px 5px 0px 0px rgba(0,0,0,1)',
    backgroundColor: '#ffffff',
  });

  // --- Create the shadow DOM ---
  const shadowRoot = widgetContainer.attachShadow({ mode: 'open' });

  // --- Inject CSS ---
  const styleLink = document.createElement('link');
  styleLink.rel = 'stylesheet';
  styleLink.href = chrome.runtime.getURL('content.css');
  shadowRoot.appendChild(styleLink);

  // --- Inject HTML ---
  const appContainer = document.createElement('div');
  appContainer.className = 'widget-root'; // Add a stable root for styling
  appContainer.innerHTML = getLoginHTML(iconUrl);
  shadowRoot.appendChild(appContainer);

  // --- Event Handling ---
  const handleClickOutside = (event) => {
    // This listener is only active when the widget is visible.
    // If the click is not on the widget container, hide it.
    if (!widgetContainer.contains(event.target)) {
      hideWidget();
    }
  };

  const showWidget = () => {
    launcher.style.display = 'none';
    widgetContainer.style.display = 'block';
    // Add the listener after the current event loop to avoid it capturing the same click that opened it.
    setTimeout(() => document.addEventListener('click', handleClickOutside), 0);
  };

  const hideWidget = () => {
    widgetContainer.style.display = 'none';
    launcher.style.display = 'flex';
    // Make sure to remove the listener when the widget is hidden.
    document.removeEventListener('click', handleClickOutside);
  };

  launcher.addEventListener('click', showWidget);

  // Use event delegation for clicks inside the widget
  shadowRoot.addEventListener('click', (event) => {
    const target = event.target.closest('button');
    if (!target) return;

    if (target.matches('.close-button')) {
      hideWidget();
    } else if (target.matches('#login-button')) {
      const usernameInput = shadowRoot.getElementById('username');
      const username = usernameInput.value.trim();

      if (!username) {
        const statusElement = shadowRoot.getElementById('status-message');
        statusElement.textContent = 'Please enter a username';
        statusElement.className = 'status-message error';
        return;
      }

      handleLogin(username, shadowRoot);
    }
  });

  // --- Message Listener ---
  chrome.runtime.onMessage.addListener((message, sender, sendResponse) => {
    if (message.action === 'ping') {
      sendResponse({ status: 'pong' });
    } else if (message.action === 'toggleMensaheWidget') {
      // This now toggles the LAUNCHER, not the widget directly.
      const isLauncherVisible = launcher.style.display !== 'none';
      if (isLauncherVisible) {
        widgetContainer.style.display = 'none'; // Ensure widget is hidden
        launcher.style.display = 'none';
      } else {
        launcher.style.display = 'flex';
      }
    }
    return true; // Keep channel open for async response
  });
}

// --- Main Execution ---
if (!document.getElementById(WIDGET_CONTAINER_ID)) {
  initializeMensaheWidget();
}
