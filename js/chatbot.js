/**
 * MIJON SKINCARE CLINIC - CHATBOT WIDGET
 * Preset Q&A + Booking Form → Saves to Admin Panel
 */

(function () {
    'use strict';

    // ─── KNOWLEDGE BASE ──────────────────────────────────────────────────────────
    const KB = {
        services: {
            reply: `At Mijon Skincare Clinic, we offer a comprehensive range of treatments:<br><br>
🔬 <b>Dermatology</b> – Acne, eczema, allergy, fungal infections, psoriasis<br>
✨ <b>Cosmetology</b> – Pigmentation, wrinkles, dark spots, skin brightening<br>
💉 <b>Botox & Fillers</b> – Anti-aging injections for smoother, youthful skin<br>
⚡ <b>Laser Surgery</b> – Acne scar removal, dark spots, mole & wart removal<br>
💧 <b>HydraFacial</b> – Deep cleansing and hydration facial treatment<br>
🌿 <b>Chemical Peeling</b> – Glycolic, salicylic, and advance peeling<br>
🪞 <b>Carbon Laser Facial</b> – The Hollywood glow treatment<br>
💊 <b>PRP / PRF Therapy</b> – Natural healing for hair and skin<br>
✂️ <b>Hair Transplant</b> – FUE-based permanent hair restoration<br>
🏥 <b>Plastic Surgery</b> – Corrective and cosmetic procedures`,
            quick: ['📅 Book Appointment', '💰 Consultation Fees', '🔙 Main Menu']
        },
        location: {
            reply: `📍 <b>Our Clinic Address:</b><br><br>
<b>Mijon Skin & Plastic Surgery Hospital Pvt. Ltd.</b><br>
Main Road, Damak-5, Jhapa<br>
Koshi Province, Nepal<br><br>
We are conveniently located in the heart of Damak city, easily accessible from Birtamode, Urlabari, and surrounding areas.`,
            quick: ['⏰ Opening Hours', '📞 Contact Numbers', '📅 Book Appointment', '🔙 Main Menu']
        },
        hours: {
            reply: `⏰ <b>Our Opening Hours:</b><br><br>
🗓️ <b>Monday – Sunday</b><br>
🕘 9:00 AM – 5:00 PM<br><br>
⚠️ <b>Please Note:</b> 11:00 AM – 2:00 PM is our busiest time. We recommend booking early morning or late afternoon for shorter wait times.<br><br>
Walk-ins are welcome, but prior appointment is preferred for specialized treatments.`,
            quick: ['📅 Book Appointment', '📞 Contact Numbers', '🔙 Main Menu']
        },
        fees: {
            reply: `💰 <b>Consultation & Treatment Fees:</b><br><br>
✅ <b>Free Skin Consultation</b> – We offer a free initial consultation so you know the best treatment for your skin before spending anything!<br><br>
Treatment costs vary depending on the procedure. Some examples:<br>
• HydraFacial – Affordable packages available<br>
• Laser Sessions – Priced per session or package deal<br>
• Botox – Unit-based pricing<br><br>
For exact pricing, please call us or visit the clinic for your free consultation.`,
            quick: ['📅 Book Appointment', '📞 Contact Numbers', '🔙 Main Menu']
        },
        contact: {
            reply: `📞 <b>Contact Us:</b><br><br>
📱 <b>Mobile:</b> <a href="tel:+9779825951131" style="color:#1a3a5c;font-weight:600;">9825951131</a><br>
📱 <b>Mobile:</b> <a href="tel:+9779842764665" style="color:#1a3a5c;font-weight:600;">9842764665</a><br>
☎️ <b>Landline:</b> <a href="tel:023585411" style="color:#1a3a5c;font-weight:600;">023-585411</a><br><br>
💬 You can also message us on Facebook or book directly through our website!`,
            quick: ['📅 Book Appointment', '📍 Location', '🔙 Main Menu']
        },
        acne: {
            reply: `🩺 <b>Acne & Pimple Treatment:</b><br><br>
Yes! We specialize in acne treatment using clinically proven methods:<br><br>
• <b>Medical facials</b> for active acne control<br>
• <b>Chemical peeling</b> (salicylic/glycolic) to unclog pores<br>
• <b>Laser treatment</b> for acne scars and marks<br>
• <b>Prescription medication</b> by certified dermatologists<br>
• <b>PRP therapy</b> for severe acne scarring<br><br>
Most patients see visible improvement within 4-6 sessions. Book a free consultation to get a personalized plan!`,
            quick: ['📅 Book Appointment', '💰 Fees', '🔙 Main Menu']
        },
        laser: {
            reply: `⚡ <b>Laser Treatments at Mijon:</b><br><br>
We offer a wide range of safe, effective laser treatments:<br><br>
• <b>Laser Hair Removal</b> – Permanent reduction of unwanted hair<br>
• <b>Carbon Laser Facial</b> – Instant glow, pore tightening<br>
• <b>Acne Scar Laser</b> – Resurfaces and smooths pitted scars<br>
• <b>Dark Spot & Pigmentation Laser</b><br>
• <b>Mole & Wart Removal</b> by laser<br>
• <b>Birthmark Reduction</b><br><br>
All procedures are performed by trained specialists using certified equipment.`,
            quick: ['📅 Book Appointment', '💰 Fees', '🔙 Main Menu']
        },
        hair: {
            reply: `✂️ <b>Hair Transplant & Hair Loss Treatment:</b><br><br>
We provide comprehensive hair restoration services:<br><br>
• <b>Hair Transplant Surgery</b> (FUE method) – Permanent, natural-looking results<br>
• <b>PRP / PRF Therapy</b> – Stimulates natural hair regrowth<br>
• <b>Scalp Treatment</b> – For dandruff, hair fall, scalp infections<br>
• <b>Mesotherapy</b> for hair roots<br><br>
Our hair specialists will assess your hair loss stage and recommend the best approach. A free consultation is the first step!`,
            quick: ['📅 Book Appointment', '💰 Fees', '🔙 Main Menu']
        },
        firstvisit: {
            reply: `🏥 <b>What to Expect on Your First Visit:</b><br><br>
1. <b>Welcome & Registration</b> – Our friendly staff will greet you<br>
2. <b>Free Skin Analysis</b> – Your skin type and concerns are assessed<br>
3. <b>Doctor Consultation</b> – Our specialist discusses your goals<br>
4. <b>Personalized Treatment Plan</b> – Tailored to your skin & budget<br>
5. <b>Treatment (if same day)</b> – Or schedule for your preferred date<br><br>
⏱️ Consultation usually takes 20-30 minutes. Please bring any previous skin prescriptions if available.`,
            quick: ['📅 Book Appointment', '📍 Location', '🔙 Main Menu']
        }
    };

    // Main menu quick replies
    const MAIN_MENU = [
        '💆 Our Services',
        '📅 Book Appointment',
        '📍 Location',
        '⏰ Opening Hours',
        '💰 Consultation Fees',
        '📞 Contact Numbers',
        '🩺 Acne Treatment',
        '⚡ Laser Treatments',
        '✂️ Hair Transplant',
        '🏥 First Visit'
    ];

    // ─── STATE ───────────────────────────────────────────────────────────────────
    let isOpen = false;
    let bookingStep = null; // null | 'name' | 'phone' | 'service' | 'date' | 'confirm'
    let bookingData = {};

    // ─── BUILD HTML ──────────────────────────────────────────────────────────────
    function buildWidget() {
        const html = `
        <!-- Chat Trigger Button -->
        <button id="chat-trigger" aria-label="Open chat">
            <svg class="icon-chat" viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-2 12H6v-2h12v2zm0-3H6V9h12v2zm0-3H6V6h12v2z"/></svg>
            <svg class="icon-close" viewBox="0 0 24 24"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>
            <span id="chat-badge">1</span>
        </button>

        <!-- Chat Window -->
        <div id="chat-window" role="dialog" aria-label="Mijon Clinic Chat">
            <div id="chat-header">
                <div class="chat-avatar">🏥</div>
                <div class="chat-header-info">
                    <div class="chat-header-name">Mijon Skincare Clinic</div>
                    <div class="chat-header-status">
                        <span class="status-dot"></span>
                        Online Now – Typically replies instantly
                    </div>
                </div>
                <button class="chat-header-close" id="chat-close-btn" aria-label="Close chat">✕</button>
            </div>

            <div id="chat-messages"></div>

            <div id="chat-footer">
                <input id="chat-input" type="text" placeholder="Type a message..." autocomplete="off" />
                <button id="chat-send" aria-label="Send">
                    <svg viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
                </button>
            </div>
            <div class="chat-powered">Powered by <span>Mijon Clinic</span> · Damak, Nepal</div>
        </div>`;

        const wrapper = document.createElement('div');
        wrapper.innerHTML = html;
        document.body.appendChild(wrapper);
    }

    // ─── MESSAGING ───────────────────────────────────────────────────────────────
    function addMessage(text, sender = 'bot', isHTML = false) {
        const messages = document.getElementById('chat-messages');
        const msgDiv = document.createElement('div');
        msgDiv.className = `chat-msg ${sender}`;

        if (sender === 'bot') {
            msgDiv.innerHTML = `
                <div class="msg-avatar">🏥</div>
                <div class="msg-bubble">${isHTML ? text : escapeHtml(text)}</div>`;
        } else {
            msgDiv.innerHTML = `<div class="msg-bubble">${escapeHtml(text)}</div>`;
        }

        messages.appendChild(msgDiv);
        scrollToBottom();
        return msgDiv;
    }

    function showTyping() {
        const messages = document.getElementById('chat-messages');
        const typingDiv = document.createElement('div');
        typingDiv.className = 'chat-msg bot';
        typingDiv.id = 'typing-indicator';
        typingDiv.innerHTML = `
            <div class="msg-avatar">🏥</div>
            <div class="typing-indicator">
                <div class="typing-dot"></div>
                <div class="typing-dot"></div>
                <div class="typing-dot"></div>
            </div>`;
        messages.appendChild(typingDiv);
        scrollToBottom();
    }

    function removeTyping() {
        const t = document.getElementById('typing-indicator');
        if (t) t.remove();
    }

    function botReply(text, quickReplies = [], delay = 700) {
        showTyping();
        setTimeout(() => {
            removeTyping();
            addMessage(text, 'bot', true);
            if (quickReplies.length > 0) {
                showQuickReplies(quickReplies);
            }
        }, delay);
    }

    function showQuickReplies(options) {
        const messages = document.getElementById('chat-messages');
        const qrDiv = document.createElement('div');
        qrDiv.className = 'quick-replies';
        options.forEach(opt => {
            const btn = document.createElement('button');
            btn.className = 'quick-reply-btn';
            btn.textContent = opt;
            btn.addEventListener('click', () => {
                qrDiv.remove();
                handleUserInput(opt);
            });
            qrDiv.appendChild(btn);
        });
        messages.appendChild(qrDiv);
        scrollToBottom();
    }

    function scrollToBottom() {
        const messages = document.getElementById('chat-messages');
        setTimeout(() => { messages.scrollTop = messages.scrollHeight; }, 50);
    }

    // ─── BOOKING FORM ─────────────────────────────────────────────────────────────
    const SERVICES_LIST = [
        'Free Skin Consultation',
        'Laser Hair Removal',
        'HydraFacial',
        'Acne & Scar Treatment',
        'Chemical Peels',
        'Botox & Fillers',
        'Microneedling',
        'PRP Therapy',
        'Anti-Aging Therapy',
        'Pigmentation Treatment'
    ];

    const TIME_SLOTS = ['Morning (9AM–11AM)', 'Afternoon (12PM–3PM)', 'Evening (3PM–5PM)'];

    function showBookingForm() {
        showTyping();
        setTimeout(() => {
            removeTyping();
            addMessage('Please fill in the details below to request an appointment. Our team will call you to confirm! 📞', 'bot', true);

            const messages = document.getElementById('chat-messages');
            const formDiv = document.createElement('div');
            formDiv.className = 'chat-msg bot';

            const today = new Date().toISOString().split('T')[0];

            const serviceOptions = SERVICES_LIST.map(s =>
                `<option value="${s}">${s}</option>`
            ).join('');

            const timeOptions = TIME_SLOTS.map(t =>
                `<option value="${t}">${t}</option>`
            ).join('');

            formDiv.innerHTML = `
                <div class="msg-avatar">🏥</div>
                <form class="chat-form" id="chat-booking-form">
                    <input type="text" id="cb-name" placeholder="Your Full Name *" required />
                    <input type="tel" id="cb-phone" placeholder="Your Phone Number *" required />
                    <input type="email" id="cb-email" placeholder="Email Address (optional)" />
                    <select id="cb-service" required>
                        <option value="" disabled selected>Please select a service *</option>
                        ${serviceOptions}
                    </select>
                    <input type="date" id="cb-date" min="${today}" />
                    <select id="cb-time" required>
                        <option value="" disabled selected>Please select a time *</option>
                        ${timeOptions}
                    </select>
                    <button type="submit" class="chat-form-submit">📅 Request Appointment</button>
                </form>`;

            messages.appendChild(formDiv);
            scrollToBottom();

            document.getElementById('chat-booking-form').addEventListener('submit', handleBookingSubmit);
        }, 600);
    }

    async function handleBookingSubmit(e) {
        e.preventDefault();
        const name    = document.getElementById('cb-name').value.trim();
        const phone   = document.getElementById('cb-phone').value.trim();
        const email   = document.getElementById('cb-email').value.trim();
        const service = document.getElementById('cb-service').value;
        const date    = document.getElementById('cb-date').value;
        const time    = document.getElementById('cb-time').value;

        if (!name || !phone) return;

        const btn = document.querySelector('.chat-form-submit');
        btn.textContent = 'Sending...';
        btn.disabled = true;

        addMessage(`📋 ${name} | ${phone} | ${service} | ${date || 'Flexible date'} | ${time}`, 'user');

        try {
            const response = await fetch('/api/save_chat_lead.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    name: name,
                    phone: phone,
                    email: email,
                    service: service || 'Free Skin Consultation',
                    date: date,
                    time: time,
                    message: `Chatbot booking — Service: ${service}, Date: ${date || 'Flexible'}, Time: ${time}${email ? ', Email: ' + email : ''}`
                })
            });

            const result = await response.json();
            document.getElementById('chat-booking-form').closest('.chat-msg').remove();

            if (result.success) {
                showBookingSuccess(name);
            } else {
                botReply(`Sorry, could not save your request. Please call <a href="tel:9825951131" style="color:#1a3a5c;font-weight:600;">9825951131</a> directly.`, [], 300);
            }
        } catch (err) {
            document.getElementById('chat-booking-form').closest('.chat-msg').remove();
            botReply(`Please call us at <a href="tel:9825951131" style="color:#D4AF37;font-weight:700;">9825951131</a> to book your appointment.`, [], 300);
        }
    }

    function showBookingSuccess(name) {
        const messages = document.getElementById('chat-messages');
        const successDiv = document.createElement('div');
        successDiv.className = 'chat-msg bot';
        successDiv.innerHTML = `
            <div class="msg-avatar">🏥</div>
            <div class="chat-success">
                <span class="success-icon">🎉</span>
                <strong>Thank you, ${name.split(' ')[0]}!</strong><br>
                Your appointment request has been received. Our team will call you shortly to confirm the date and time.<br><br>
                <strong>📞 023-585411 / 9825951131</strong>
            </div>`;
        messages.appendChild(successDiv);
        showQuickReplies(['💆 Our Services', '📍 Location', '🔙 Main Menu']);
        scrollToBottom();
    }

    // ─── INPUT HANDLER ───────────────────────────────────────────────────────────
    function handleUserInput(input) {
        const text = input.trim();
        if (!text) return;

        // Only add as user message if it's not a quick reply button press with icon
        if (!text.includes('🔙') && !text.includes('📅') && !text.includes('💆') &&
            !text.includes('📍') && !text.includes('⏰') && !text.includes('💰') &&
            !text.includes('📞') && !text.includes('🩺') && !text.includes('⚡') &&
            !text.includes('✂️') && !text.includes('🏥') && !text.includes('💉')) {
            addMessage(text, 'user');
        } else {
            addMessage(text, 'user');
        }

        const lower = text.toLowerCase();

        // Routing logic
        if (lower.includes('main menu') || lower.includes('menu') || lower.includes('back') || lower === 'hi' || lower === 'hello' || lower === 'hey') {
            showMainMenu();
        } else if (lower.includes('service') || lower.includes('treatment') || lower.includes('offer')) {
            botReply(KB.services.reply, KB.services.quick);
        } else if (lower.includes('book') || lower.includes('appointment') || lower.includes('schedule')) {
            showBookingForm();
        } else if (lower.includes('location') || lower.includes('address') || lower.includes('where') || lower.includes('find')) {
            botReply(KB.location.reply, KB.location.quick);
        } else if (lower.includes('hour') || lower.includes('open') || lower.includes('time') || lower.includes('when')) {
            botReply(KB.hours.reply, KB.hours.quick);
        } else if (lower.includes('fee') || lower.includes('price') || lower.includes('cost') || lower.includes('charge') || lower.includes('consultation fees')) {
            botReply(KB.fees.reply, KB.fees.quick);
        } else if (lower.includes('contact') || lower.includes('phone') || lower.includes('number') || lower.includes('call')) {
            botReply(KB.contact.reply, KB.contact.quick);
        } else if (lower.includes('acne') || lower.includes('pimple') || lower.includes('zit') || lower.includes('spot')) {
            botReply(KB.acne.reply, KB.acne.quick);
        } else if (lower.includes('laser')) {
            botReply(KB.laser.reply, KB.laser.quick);
        } else if (lower.includes('hair')) {
            botReply(KB.hair.reply, KB.hair.quick);
        } else if (lower.includes('first visit') || lower.includes('first time') || lower.includes('what to expect')) {
            botReply(KB.firstvisit.reply, KB.firstvisit.quick);
        } else if (lower.includes('thank')) {
            botReply('You are most welcome! 😊 We look forward to seeing you at Mijon Skincare Clinic. Is there anything else I can help you with?', ['📅 Book Appointment', '🔙 Main Menu']);
        } else {
            // Fallback
            botReply(`I'm not sure I understood that completely. Here are some things I can help you with:`, MAIN_MENU);
        }
    }

    function showMainMenu() {
        botReply('👋 Hello! Welcome to <b>Mijon Skincare Clinic</b>, Damak\'s premier skin and aesthetic hospital. How can I help you today?', MAIN_MENU);
    }

    // ─── INIT ────────────────────────────────────────────────────────────────────
    function init() {
        buildWidget();

        const trigger = document.getElementById('chat-trigger');
        const chatWindow = document.getElementById('chat-window');
        const closeBtn = document.getElementById('chat-close-btn');
        const sendBtn = document.getElementById('chat-send');
        const input = document.getElementById('chat-input');
        const badge = document.getElementById('chat-badge');

        function openChat() {
            isOpen = true;
            chatWindow.classList.add('open');
            trigger.classList.add('open');
            badge.style.display = 'none';

            // Send welcome message only on first open
            if (document.getElementById('chat-messages').children.length === 0) {
                setTimeout(() => showMainMenu(), 400);
            }
        }

        function closeChat() {
            isOpen = false;
            chatWindow.classList.remove('open');
            trigger.classList.remove('open');
        }

        trigger.addEventListener('click', (e) => { 
            e.stopPropagation();
            isOpen ? closeChat() : openChat(); 
        });
        
        closeBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            closeChat();
        });

        chatWindow.addEventListener('click', (e) => {
            e.stopPropagation();
        });

        sendBtn.addEventListener('click', () => {
            const val = input.value.trim();
            if (val) {
                handleUserInput(val);
                input.value = '';
            }
        });

        input.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                const val = input.value.trim();
                if (val) {
                    handleUserInput(val);
                    input.value = '';
                }
            }
        });

        // Handle click outside to close
        document.addEventListener('click', (e) => {
            if (isOpen && 
                !chatWindow.contains(e.target) && 
                !trigger.contains(e.target)) {
                closeChat();
            }
        });
    }

    // ─── UTILS ───────────────────────────────────────────────────────────────────
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.appendChild(document.createTextNode(text));
        return div.innerHTML;
    }

    // Boot
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();
