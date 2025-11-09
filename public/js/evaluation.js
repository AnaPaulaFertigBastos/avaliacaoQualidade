document.addEventListener('DOMContentLoaded', function(){
    const questions = Array.from(document.querySelectorAll('.question'));
    const nextBtn = document.getElementById('nextBtn');
    const prevBtn = document.getElementById('prevBtn');
    const submitBtn = document.querySelector('.submit-btn');
    const form = document.getElementById('evalForm');
    let index = 0;

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
        if(!current) return true;
        const radios = current.querySelectorAll('input[type="radio"]');
        for(const r of radios){ if(r.checked) return true; }
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

    // on submit, ensure all questions answered
    form.addEventListener('submit', function(e){
        for(const q of questions){
            const radios = q.querySelectorAll('input[type="radio"]');
            let ok = false;
            for(const r of radios){ if(r.checked){ ok = true; break; } }
            if(!ok){
                e.preventDefault();
                alert('Por favor responda todas as perguntas antes de enviar.');
                // show the unanswered question
                const idx = questions.indexOf(q);
                index = idx;
                showIndex(index);
                return false;
            }
        }
    });

    // add a border on selected inputs
    document.querySelectorAll('.scale-label').forEach(lbl => {
        lbl.addEventListener('click', function(e){
            // find the input inside this label
            const input = lbl.querySelector('input[type="radio"]');
            if(!input) return;

            // unselect siblings
            const container = lbl.closest('.scale');
            if(container){
                container.querySelectorAll('.scale-label').forEach(sib => sib.classList.remove('selected'));
            }

            // select this one
            input.checked = true;
            lbl.classList.add('selected');

            // trigger change for any other listeners
            input.dispatchEvent(new Event('change', { bubbles: true }));
        });

        // keyboard accessibility
        lbl.setAttribute('tabindex', '0');
        lbl.addEventListener('keydown', function(e){
            if(e.key === 'Enter' || e.key === ' '){
                e.preventDefault();
                lbl.click();
            }
        });
    });

    // initialize
    showIndex(index);
});
