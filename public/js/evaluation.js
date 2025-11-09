document.addEventListener('DOMContentLoaded', function(){
    // questions will be populated dynamically after AJAX fetch
    let questions = [];
    const nextBtn = document.getElementById('nextBtn');
    const prevBtn = document.getElementById('prevBtn');
    const submitBtn = document.querySelector('.submit-btn');
    const form = document.getElementById('evalForm');
    let index = 0;
    const questionsWrap = document.getElementById('questionsWrap');
    const selectedSetor = questionsWrap ? questionsWrap.dataset.setor || '' : '';
    const selectedDevice = questionsWrap ? questionsWrap.dataset.device || '' : '';

    function showIndex(i){
        questions.forEach(q=>{
            q.setAttribute('aria-hidden','true');
        });
        const current = questions[i];
        if(current){
            current.setAttribute('aria-hidden','false');
        }
        prevBtn.hidden = (i===0);
        // if last question, hide next and show submit
        if(i === questions.length - 1){
            nextBtn.style.display = 'none';
            submitBtn.style.display = '';
        } else {
            nextBtn.style.display = '';
            submitBtn.style.display = 'none';
        }
    }

    function currentAnswered(){
        const current = questions[index];
        if(!current) 
            return true;
        const radios = current.querySelectorAll('input[type="radio"]');
        for(const r of radios){ 
            if(r.checked) return true; 
        }
        // also consider textarea answers as answered
        const textarea = current.querySelector('textarea');
        if(textarea && textarea.value.trim() !== '') 
            return true;
        return false;
    }

    nextBtn.addEventListener('click', function(){
        if(index < questions.length - 1){
            index++;
            showIndex(index);
            window.scrollTo({top:0,behavior:'smooth'});
        }
    });

    prevBtn.addEventListener('click', function(){
        if(index > 0){
            index--;
            showIndex(index);
            window.scrollTo({top:0,behavior:'smooth'});
        }
    });

    function anexarManipuladoresScale(){
        document.querySelectorAll('.scale-label').forEach(lbl => {
            // avoid adding duplicate listeners
            if (lbl.__bound) return;
            lbl.__bound = true;

            lbl.addEventListener('click', function(e){
                const input = lbl.querySelector('input[type="radio"]');
                if(!input) return;

                // unselect siblings
                const container = lbl.closest('.scale');
                if(container){
                    container.querySelectorAll('.scale-label').forEach(sib => sib.classList.remove('selected'));
                }

                input.checked = true;
                lbl.classList.add('selected');
                input.dispatchEvent(new Event('change', { bubbles: true }));
            });

            lbl.setAttribute('tabindex', '0');
            lbl.addEventListener('keydown', function(e){
                if(e.key === 'Enter' || e.key === ' '){
                    e.preventDefault();
                    lbl.click();
                }
            });
        });
    }

    // auto-advance after selecting an option (if there is a next question)
    let autoAdvanceTimer = null;
    const AUTO_ADVANCE_DELAY = 350; // ms

    function anexarAutoAvancoRadios(){
        document.querySelectorAll('input[type="radio"]').forEach(input => {
            if (input.__autoBound) return;
            input.__autoBound = true;
            input.addEventListener('change', function(){
                if(index < questions.length - 1){
                    if(autoAdvanceTimer) clearTimeout(autoAdvanceTimer);
                    autoAdvanceTimer = setTimeout(() => {
                        if(index < questions.length - 1){
                            nextBtn.click();
                        }
                    }, AUTO_ADVANCE_DELAY);
                }
            });
        });
    }

    // renderiza perguntas recebidas do servidor e inicializa controles
    function renderizarPerguntasNoDOM(fetchedQuestions){
        const wrap = document.getElementById('questionsWrap');
        if(!wrap) return;
        wrap.innerHTML = ''; // clear loading

        fetchedQuestions.forEach((q, idx) => {
            const qDiv = document.createElement('div');
            qDiv.className = 'question';
            qDiv.dataset.index = idx;
            qDiv.setAttribute('aria-hidden', idx === 0 ? 'false' : 'true');

            let inner = `<div class="question-inner">`;
            inner += `<p class="question-number"><strong>${idx+1} / ${fetchedQuestions.length}</strong></p>`;
            inner += `<p class="question-text"><strong>${escaparHtml(q.texto)}</strong></p>`;

            if(!q.resposta_numerica){
                inner += `<div class="feedback-row"><textarea name="responses[${q.id}]" class="feedback" rows="4" style="width:100%"></textarea></div>`;
            } else {
                inner += `<div class="scale">`;
                for(let i=0;i<=10;i++){
                    const r = 255 - (i * 25);
                    const g = i * 20;
                    const color = `rgb(${r}, ${g}, 0)`;
                    inner += `<label class="scale-label"><input type="radio" name="responses[${q.id}]" value="${i}" style="display:none;"><span class="scale-value" style="background-color: ${color};">${i}</span></label>`;
                }
                inner += `</div>`;
            }

            inner += `</div>`;
            qDiv.innerHTML = inner;
            wrap.appendChild(qDiv);
        });

    // update questions NodeList
    questions = Array.from(document.querySelectorAll('.question'));

    // attach handlers
    anexarManipuladoresScale();
    anexarAutoAvancoRadios();

        // initialize UI state
        index = 0;
        showIndex(index);
    }

    function escaparHtml(unsafe){
        return String(unsafe)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    // fetch questions via AJAX
    (function fetchQuestions(){
        const urlBase = '/avaliacao/questions';
        let url = urlBase;
        if(selectedSetor && selectedDevice){
            url = `${urlBase}/${encodeURIComponent(selectedSetor)}/${encodeURIComponent(selectedDevice)}`;
        }

        fetch(url, { headers: { 'Accept': 'application/json' } })
            .then(r => r.json())
            .then(data => {
                renderizarPerguntasNoDOM(data);
            })
            .catch(err => {
                const wrap = document.getElementById('questionsWrap');
                if(wrap) wrap.innerHTML = '<p class="error">Erro ao carregar perguntas. Atualize a página.</p>';
                console.error('Erro ao buscar perguntas:', err);
            });
    })();
});
