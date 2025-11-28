document.addEventListener('DOMContentLoaded', () => {
    
    // --- 1. DADOS SIMULADOS (MOCK DATA) ---
    const currentUser = { 
        nome: "Aluno Senai", 
        avatar: "👤", // Ou inicial 'AS'
        tipo: "aluno" 
    };

    const messages = [
        {
            autor: "Prof. João Silva",
            iniciais: "JS",
            texto: "Olá! Como está o andamento do projeto da suspensão?",
            hora: "10:30",
            tipo: "recebida",
            status: "online"
        },
        {
            autor: currentUser.nome,
            iniciais: "AS",
            texto: "Bom dia, professor! Já finalizei a modelagem 3D. Estou montando a apresentação agora.",
            hora: "10:32",
            tipo: "enviada" // 'own'
        },
        {
            autor: "Prof. João Silva",
            iniciais: "JS",
            texto: "Excelente! Se tiver dúvidas no cálculo de carga, pode me chamar aqui.",
            hora: "10:33",
            tipo: "recebida",
            status: "online"
        }
    ];

    // --- 2. SELETORES DO DOM ---
    const msgContainer = document.getElementById('msgContainer');
    const messageInput = document.querySelector('.message-input');
    const sendBtn = document.querySelector('.send-btn');

    // --- 3. FUNÇÃO PARA RENDERIZAR MENSAGENS ---
    function renderMessages() {
        // Limpa o container (opcional, se quiser redesenhar tudo)
        msgContainer.innerHTML = '';

        messages.forEach(msg => {
            const isOwn = msg.tipo === 'enviada';
            
            // Cria o HTML da mensagem
            const msgDiv = document.createElement('div');
            msgDiv.className = `message ${isOwn ? 'own' : ''}`;

            // Avatar (só mostra se não for o próprio usuário, opcional)
            // Mas seu CSS atual mostra avatar para ambos, então vamos manter.
            const avatarDiv = document.createElement('div');
            avatarDiv.className = 'participant-avatar';
            avatarDiv.style.width = '32px';
            avatarDiv.style.height = '32px';
            avatarDiv.style.fontSize = '12px';
            avatarDiv.textContent = msg.iniciais;
            // Se for professor, pode mudar a cor de fundo manualmente ou via classe
            if(!isOwn) avatarDiv.style.background = 'linear-gradient(135deg, #7c3aed, #4f46e5)';

            // Conteúdo (Bolha + Hora)
            const contentDiv = document.createElement('div');
            
            const bubble = document.createElement('div');
            bubble.className = 'message-bubble';
            bubble.textContent = msg.texto;

            const timeSpan = document.createElement('span');
            timeSpan.className = 'message-time';
            timeSpan.textContent = msg.hora;

            // Monta a estrutura
            contentDiv.appendChild(bubble);
            contentDiv.appendChild(timeSpan);

            msgDiv.appendChild(avatarDiv);
            msgDiv.appendChild(contentDiv);

            msgContainer.appendChild(msgDiv);
        });

        // Rola para o final
        msgContainer.scrollTop = msgContainer.scrollHeight;
    }

    // --- 4. FUNÇÃO PARA ENVIAR MENSAGEM ---
    function sendMessage() {
        const text = messageInput.value.trim();
        
        if (text !== "") {
            // Pega a hora atual (HH:MM)
            const now = new Date();
            const hora = now.getHours().toString().padStart(2, '0') + ':' + 
                         now.getMinutes().toString().padStart(2, '0');

            // Adiciona ao array (simulação de backend)
            messages.push({
                autor: currentUser.nome,
                iniciais: "AS",
                texto: text,
                hora: hora,
                tipo: "enviada"
            });

            // Limpa input e renderiza
            messageInput.value = '';
            renderMessages();
        }
    }

    // --- 5. EVENTOS ---
    
    // Clique no botão enviar
    sendBtn.addEventListener('click', sendMessage);

    // Enter para enviar
    messageInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            sendMessage();
        }
    });

    // --- INICIALIZAÇÃO ---
    renderMessages();
});