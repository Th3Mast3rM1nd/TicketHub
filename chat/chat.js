(function () {
  if (document.getElementById('chat-bubble')) return;

  /* ── Styles ── */
  var style = document.createElement('style');
  style.textContent = [
    '@keyframes msgIn {',
    '  from { opacity:0; transform:translateY(10px); }',
    '  to   { opacity:1; transform:translateY(0); }',
    '}',

    /* ── Toggle button ── */
    '#chat-bubble {',
    '  position:fixed; bottom:24px; right:24px; z-index:200;',
    '}',
    '#chat-toggle {',
    '  width:52px; height:52px; border-radius:50%;',
    '  background:#4F46E5; color:#fff; border:none;',
    '  display:flex; align-items:center; justify-content:center;',
    '  cursor:pointer; box-shadow:0 4px 20px rgba(79,70,229,0.5);',
    '  transition:transform 160ms ease, background 160ms ease, box-shadow 160ms ease;',
    '}',
    '#chat-toggle:hover { background:#3730A3; transform:scale(1.08); box-shadow:0 6px 28px rgba(79,70,229,0.65); }',
    '#chat-unread {',
    '  position:absolute; top:0; right:0;',
    '  width:12px; height:12px; border-radius:50%;',
    '  background:#EF4444; border:2px solid #0F0F13; display:none;',
    '}',

    /* ── Window (dark default) ── */
    '#chat-window {',
    '  position:absolute; bottom:68px; right:0;',
    '  width:360px; height:480px;',
    '  background:#16161C; border:1px solid #2A2A35; border-radius:16px;',
    '  box-shadow:0 24px 48px rgba(0,0,0,0.6),0 0 0 1px rgba(79,70,229,0.08);',
    '  display:flex; flex-direction:column; overflow:hidden;',
    '  font-family:"Space Grotesk",ui-sans-serif,system-ui,sans-serif; font-size:14px;',
    '  opacity:0; transform:translateY(16px) scale(0.97);',
    '  pointer-events:none;',
    '  transition:opacity 240ms cubic-bezier(0.16,1,0.3,1),',
    '             transform 240ms cubic-bezier(0.16,1,0.3,1);',
    '}',
    '#chat-window.open {',
    '  opacity:1; transform:translateY(0) scale(1); pointer-events:auto;',
    '}',

    /* ── Header (dark default) ── */
    '#chat-header {',
    '  padding:14px 16px; border-bottom:1px solid #2A2A35;',
    '  display:flex; align-items:center; justify-content:space-between;',
    '  background:#1E1E26;',
    '}',
    '#chat-header-title {',
    '  font-weight:600; font-size:13px; letter-spacing:-0.01em; color:#F0F0F5;',
    '  display:flex; align-items:center; gap:8px;',
    '}',
    '#chat-header-icon {',
    '  width:28px; height:28px; border-radius:8px;',
    '  background:rgba(79,70,229,0.18); color:#818CF8;',
    '  display:flex; align-items:center; justify-content:center;',
    '  flex-shrink:0;',
    '}',
    '#chat-header-dot {',
    '  width:7px; height:7px; border-radius:50%; background:#22C55E;',
    '  box-shadow:0 0 6px rgba(34,197,94,0.6);',
    '}',
    '#chat-close {',
    '  width:28px; height:28px; border-radius:6px;',
    '  background:none; border:none; cursor:pointer; color:#8888A0;',
    '  display:flex; align-items:center; justify-content:center;',
    '  font-size:18px; line-height:1; transition:background 120ms ease, color 120ms ease;',
    '}',
    '#chat-close:hover { background:#2A2A35; color:#F0F0F5; }',

    /* ── Messages (dark default) ── */
    '#chat-messages {',
    '  flex:1; overflow-y:auto; padding:16px;',
    '  display:flex; flex-direction:column; gap:10px;',
    '  scrollbar-width:thin; scrollbar-color:#2A2A35 transparent;',
    '}',
    '.chat-msg { display:flex; flex-direction:column; max-width:85%; gap:3px;',
    '  opacity:0; animation:msgIn 0.3s cubic-bezier(0.16,1,0.3,1) forwards; }',
    '.chat-msg.user { align-self:flex-end; align-items:flex-end; }',
    '.chat-msg.assistant { align-self:flex-start; align-items:flex-start; }',
    '.chat-bubble {',
    '  padding:9px 13px; border-radius:12px;',
    '  line-height:1.55; word-break:break-word; white-space:pre-wrap; font-size:13px;',
    '}',
    '.chat-msg.user .chat-bubble {',
    '  background:#4F46E5; color:#fff; border-bottom-right-radius:3px;',
    '}',
    '.chat-msg.assistant .chat-bubble {',
    '  background:#1E1E26; color:#F0F0F5; border:1px solid #2A2A35;',
    '  border-bottom-left-radius:3px;',
    '}',
    '.chat-time { font-size:10px; color:#555568; font-family:"Space Mono",monospace; }',
    '#chat-thinking {',
    '  align-self:flex-start;',
    '  padding:9px 13px; border-radius:12px; border-bottom-left-radius:3px;',
    '  background:#1E1E26; border:1px solid #2A2A35; color:#8888A0; font-size:13px;',
    '  opacity:0; animation:msgIn 0.3s cubic-bezier(0.16,1,0.3,1) forwards;',
    '}',

    /* ── Input row (dark default) ── */
    '#chat-input-row {',
    '  padding:12px 14px; border-top:1px solid #2A2A35;',
    '  display:flex; gap:8px; background:#16161C;',
    '}',
    '#chat-input {',
    '  flex:1; padding:8px 12px; border:1px solid #2A2A35; border-radius:8px;',
    '  font:inherit; font-size:13px; outline:none;',
    '  background:#1E1E26; color:#F0F0F5;',
    '  transition:border-color 150ms ease, box-shadow 150ms ease;',
    '}',
    '#chat-input::placeholder { color:#555568; }',
    '#chat-input:focus { border-color:#4F46E5; box-shadow:0 0 0 3px rgba(79,70,229,0.15); }',
    '#chat-send {',
    '  padding:0 14px; height:36px; border-radius:8px;',
    '  background:#4F46E5; color:#fff; border:none;',
    '  font:inherit; font-size:13px; font-weight:600; cursor:pointer;',
    '  transition:background 160ms ease, transform 160ms ease;',
    '}',
    '#chat-send:hover { background:#3730A3; transform:translateY(-1px); }',
    '#chat-send:active { transform:translateY(0); }',
    '#chat-send:disabled { background:rgba(79,70,229,0.3); cursor:not-allowed; transform:none; }',

    /* ════════════════════════════════════
       Light mode overrides
       ════════════════════════════════════ */
    '[data-theme="light"] #chat-unread { border-color:#FFFFFF; }',

    '[data-theme="light"] #chat-window {',
    '  background:#FFFFFF; border-color:#E5E7EB;',
    '  box-shadow:0 16px 40px rgba(0,0,0,0.12),0 0 0 1px rgba(79,70,229,0.06);',
    '}',
    '[data-theme="light"] #chat-header {',
    '  background:#F9FAFB; border-bottom-color:#E5E7EB;',
    '}',
    '[data-theme="light"] #chat-header-title { color:#111827; }',
    '[data-theme="light"] #chat-header-icon { background:rgba(79,70,229,0.10); color:#4F46E5; }',
    '[data-theme="light"] #chat-close { color:#9CA3AF; }',
    '[data-theme="light"] #chat-close:hover { background:#F3F4F6; color:#111827; }',
    '[data-theme="light"] #chat-messages { scrollbar-color:#E5E7EB transparent; }',
    '[data-theme="light"] .chat-msg.assistant .chat-bubble {',
    '  background:#F3F4F6; color:#111827; border-color:#E5E7EB;',
    '}',
    '[data-theme="light"] .chat-time { color:#9CA3AF; }',
    '[data-theme="light"] #chat-thinking {',
    '  background:#F3F4F6; border-color:#E5E7EB; color:#6B7280;',
    '}',
    '[data-theme="light"] #chat-input-row {',
    '  background:#FFFFFF; border-top-color:#E5E7EB;',
    '}',
    '[data-theme="light"] #chat-input {',
    '  background:#FFFFFF; color:#111827; border-color:#D1D5DB;',
    '}',
    '[data-theme="light"] #chat-input::placeholder { color:#9CA3AF; }',
  ].join('\n');
  document.head.appendChild(style);

  /* ── HTML ── */
  var widget = document.createElement('div');
  widget.id = 'chat-bubble';
  widget.innerHTML =
    '<div id="chat-window">' +
      '<div id="chat-header">' +
        '<div id="chat-header-title">' +
          '<div id="chat-header-icon">' +
            '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' +
              '<path d="M12 2l2.4 7.4H22l-6.2 4.5 2.4 7.4L12 17l-6.2 4.3 2.4-7.4L2 9.4h7.6z"/>' +
            '</svg>' +
          '</div>' +
          '<div id="chat-header-dot"></div>' +
          'AI Assistant' +
        '</div>' +
        '<button id="chat-close" aria-label="Close chat">\xd7</button>' +
      '</div>' +
      '<div id="chat-messages"></div>' +
      '<div id="chat-input-row">' +
        '<input id="chat-input" type="text" placeholder="Ask about your tickets…" autocomplete="off" maxlength="1000">' +
        '<button id="chat-send">Send</button>' +
      '</div>' +
    '</div>' +
    '<button id="chat-toggle" aria-label="Open AI chat">' +
      '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' +
        '<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>' +
      '</svg>' +
    '</button>' +
    '<div id="chat-unread"></div>';
  document.body.appendChild(widget);

  /* ── State ── */
  var isOpen    = false;
  var isLoading = false;

  /* ── Elements ── */
  var win      = document.getElementById('chat-window');
  var toggle   = document.getElementById('chat-toggle');
  var closeBtn = document.getElementById('chat-close');
  var messages = document.getElementById('chat-messages');
  var input    = document.getElementById('chat-input');
  var sendBtn  = document.getElementById('chat-send');
  var unread   = document.getElementById('chat-unread');

  /* ── Helpers ── */
  function timestamp() {
    var now = new Date();
    return now.getHours().toString().padStart(2, '0') + ':' +
           now.getMinutes().toString().padStart(2, '0');
  }

  function appendMessage(role, text) {
    var msg    = document.createElement('div');
    msg.className = 'chat-msg ' + role;
    var bubble = document.createElement('div');
    bubble.className = 'chat-bubble';
    bubble.textContent = text;
    var time   = document.createElement('div');
    time.className = 'chat-time';
    time.textContent = timestamp();
    msg.appendChild(bubble);
    msg.appendChild(time);
    messages.appendChild(msg);
    messages.scrollTop = messages.scrollHeight;
  }

  function showThinking() {
    var el = document.createElement('div');
    el.id = 'chat-thinking';
    el.textContent = 'Thinking…';
    messages.appendChild(el);
    messages.scrollTop = messages.scrollHeight;
  }

  function removeThinking() {
    var el = document.getElementById('chat-thinking');
    if (el) el.remove();
  }

  function setLoading(val) {
    isLoading        = val;
    sendBtn.disabled = val;
    input.disabled   = val;
  }

  /* ── Welcome message ── */
  appendMessage('assistant', 'Hi! I can help you analyse any ticket — just mention a ticket number like TH-042.');

  /* ── Open / close ── */
  function openChat()  { isOpen = true;  win.classList.add('open');    unread.style.display = 'none'; input.focus(); }
  function closeChat() { isOpen = false; win.classList.remove('open'); }

  toggle.addEventListener('click', function () { isOpen ? closeChat() : openChat(); });
  closeBtn.addEventListener('click', closeChat);
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && isOpen) closeChat();
  });

  /* ── Send ── */
  function sendMessage() {
    var text = input.value.trim();
    if (!text || isLoading) return;

    input.value = '';
    appendMessage('user', text);
    setLoading(true);
    showThinking();

    fetch('/chat/message.php', {
      method:  'POST',
      headers: { 'Content-Type': 'application/json' },
      body:    JSON.stringify({ message: text }),
    })
    .then(function (res) {
      if (res.status === 401) throw new Error('Session expired. Please refresh the page.');
      return res.json();
    })
    .then(function (data) {
      removeThinking();
      appendMessage('assistant', data.reply || data.error || 'No response.');
      if (!isOpen) { unread.style.display = 'block'; }
    })
    .catch(function (err) {
      removeThinking();
      appendMessage('assistant', err.message || 'Something went wrong. Please try again.');
    })
    .finally(function () { setLoading(false); });
  }

  sendBtn.addEventListener('click', sendMessage);
  input.addEventListener('keydown', function (e) {
    if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendMessage(); }
  });

}());
