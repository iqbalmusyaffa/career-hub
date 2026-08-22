<!-- Live Chat Slide-over Drawer Component -->
<div id="liveChatDrawer" class="fixed inset-0 z-50 overflow-hidden hidden" aria-labelledby="slide-over-title" role="dialog" aria-modal="true">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" onclick="closeLiveChat()"></div>

    <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
        <div class="pointer-events-auto w-screen max-w-md transform transition duration-300 ease-in-out">
            <div class="flex h-full flex-col bg-white shadow-2xl border-l border-slate-200">
                
                <!-- Chat Drawer Header -->
                <div class="bg-slate-900 text-white p-5 flex items-center justify-between border-b border-slate-800">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-slate-800 text-slate-200 font-bold flex items-center justify-center border border-slate-700">
                            <i class="fa-solid fa-comments text-base"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-100" id="chatHeaderTitle">Live Chat Rekrutmen</h3>
                            <p class="text-3xs text-slate-400 font-medium" id="chatHeaderSubtitle">Menghubungkan Pelamar & Tim HR</p>
                        </div>
                    </div>
                    <button onclick="closeLiveChat()" class="text-slate-400 hover:text-white transition p-2 rounded-lg hover:bg-slate-800">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <!-- Chat Messages Body -->
                <div id="chatMessagesContainer" class="flex-1 overflow-y-auto p-5 space-y-3.5 bg-slate-50/50">
                    <div class="text-center py-8 text-slate-400 text-xs">
                        <i class="fa-solid fa-spinner fa-spin text-xl mb-2 text-slate-600 block"></i>
                        <span>Memuat percakapan...</span>
                    </div>
                </div>

                <!-- Chat Input Footer -->
                <div class="p-4 bg-white border-t border-slate-200">
                    <form id="chatForm" onsubmit="submitChatMessage(event)" class="flex items-center gap-2">
                        <input type="text" id="chatInput" placeholder="Ketik pesan Anda di sini..." class="flex-1 border-slate-300 rounded-xl text-xs focus:ring-slate-800 focus:border-slate-800 font-medium py-2.5 px-3.5" autocomplete="off" required>
                        <button type="submit" id="chatSendBtn" class="px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl transition border border-slate-900 shrink-0 flex items-center gap-1.5 shadow-2xs">
                            <i class="fa-solid fa-paper-plane text-xs"></i>
                            <span class="hidden sm:inline">Kirim</span>
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    let activeApplicationId = null;
    let chatPollInterval = null;

    function openLiveChat(applicationId, jobTitle, opponentName) {
        activeApplicationId = applicationId;
        document.getElementById('chatHeaderTitle').innerText = jobTitle || 'Live Chat Rekrutmen';
        document.getElementById('chatHeaderSubtitle').innerText = 'Percakapan dengan: ' + (opponentName || 'HR / Pelamar');
        document.getElementById('liveChatDrawer').classList.remove('hidden');
        fetchChatMessages();

        if (chatPollInterval) clearInterval(chatPollInterval);
        chatPollInterval = setInterval(fetchChatMessages, 4000);
    }

    function closeLiveChat() {
        document.getElementById('liveChatDrawer').classList.add('hidden');
        if (chatPollInterval) {
            clearInterval(chatPollInterval);
            chatPollInterval = null;
        }
        activeApplicationId = null;
    }

    function fetchChatMessages() {
        if (!activeApplicationId) return;

        fetch(`/applications/${activeApplicationId}/messages`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            const container = document.getElementById('chatMessagesContainer');
            if (!data.messages || data.messages.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-12 text-slate-400 text-xs">
                        <div class="w-12 h-12 bg-slate-100 text-slate-400 rounded-2xl flex items-center justify-center mx-auto mb-2 text-lg border border-slate-200">
                            <i class="fa-solid fa-comments"></i>
                        </div>
                        <p class="font-bold text-slate-700 mb-1">Belum Ada Pesan</p>
                        <p class="text-slate-400">Mulai diskusi langsung terkait lamaran pekerjaan ini.</p>
                    </div>
                `;
                return;
            }

            let html = '';
            data.messages.forEach(msg => {
                if (msg.is_me) {
                    html += `
                        <div class="flex flex-col items-end">
                            <div class="bg-slate-900 text-white rounded-2xl rounded-tr-xs px-4 py-2.5 max-w-[85%] text-xs shadow-2xs font-medium leading-relaxed">
                                ${escapeHtml(msg.message)}
                            </div>
                            <span class="text-3xs text-slate-400 mt-1 font-semibold">${msg.time}</span>
                        </div>
                    `;
                } else {
                    html += `
                        <div class="flex flex-col items-start">
                            <span class="text-3xs font-bold text-slate-500 mb-1 ml-1">${escapeHtml(msg.sender_name)}</span>
                            <div class="bg-white text-slate-900 border border-slate-200 rounded-2xl rounded-tl-xs px-4 py-2.5 max-w-[85%] text-xs shadow-2xs font-medium leading-relaxed">
                                ${escapeHtml(msg.message)}
                            </div>
                            <span class="text-3xs text-slate-400 mt-1 ml-1 font-semibold">${msg.time}</span>
                        </div>
                    `;
                }
            });

            const shouldScroll = container.scrollTop + container.clientHeight >= container.scrollHeight - 100 || container.children.length <= 1;
            container.innerHTML = html;
            if (shouldScroll) {
                container.scrollTop = container.scrollHeight;
            }
        })
        .catch(err => console.error('Error fetching chat messages:', err));
    }

    function submitChatMessage(e) {
        e.preventDefault();
        const input = document.getElementById('chatInput');
        const message = input.value.trim();
        if (!message || !activeApplicationId) return;

        const sendBtn = document.getElementById('chatSendBtn');
        sendBtn.disabled = true;

        fetch(`/applications/${activeApplicationId}/messages`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ message: message })
        })
        .then(res => res.json())
        .then(data => {
            sendBtn.disabled = false;
            if (data.success) {
                input.value = '';
                fetchChatMessages();
            }
        })
        .catch(err => {
            sendBtn.disabled = false;
            console.error('Error sending message:', err);
        });
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.innerText = text;
        return div.innerHTML;
    }
</script>
