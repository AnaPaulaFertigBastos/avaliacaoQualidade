(function(){
  const d = window.DASHBOARD_DATA || {};
  let fullLabelsMedias = Array.isArray(d.chartLabels) ? d.chartLabels.slice() : [];
  let valoresMedias = Array.isArray(d.chartAverages) ? d.chartAverages.slice() : [];
  let labelsDistrib = Array.isArray(d.scoreLabels) ? d.scoreLabels.slice() : [];
  let valoresDistrib = Array.isArray(d.scoreValues) ? d.scoreValues.slice() : [];
  const baseDadosUrl = d.dadosBaseUrl || '';

  function truncarTexto(label, max = 38){
    if(!label) return '';
    return label.length > max ? label.slice(0, max - 3) + '...' : label;
  }
  let labelsMedias = fullLabelsMedias.map(l => truncarTexto(l));

  let chartMediasInstance = null;
  let chartDistribInstance = null;

  function createBarChart(){
    const ctxMedias = document.getElementById('chartMedias');
    if(!ctxMedias){ console.warn('Canvas chartMedias não encontrado'); return; }
    if(chartMediasInstance){ chartMediasInstance.destroy(); }
    chartMediasInstance = new Chart(ctxMedias, {
      type: 'bar',
      data: {
        labels: labelsMedias,
        datasets: [{
          label: 'Média',
          data: valoresMedias,
          backgroundColor: 'rgba(45,106,123,0.55)',
          borderColor: 'rgba(45,106,123,1)',
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: true },
          tooltip: {
            enabled: true,
            callbacks: {
              title: items => {
                if(!items || !items.length) return '';
                const idx = items[0].dataIndex;
                return fullLabelsMedias[idx] || '';
              }
            }
          }
        },
        scales: {
          y: { beginAtZero: true, suggestedMax: 10 },
          x: {
            ticks: {
              callback: value => labelsMedias[value]
            }
          }
        }
      }
    });
  }

  function getScoreColor(score){
    const pct = score / 10;
    let hue;
    if(pct <= 0.5){ hue = 0 + (60 * (pct / 0.5)); } else { hue = 60 + (60 * ((pct - 0.5) / 0.5)); }
    const saturation = 65;
    const lightness = 58 + (pct * 6);
    return {
      bg: `hsla(${hue}, ${saturation}%, ${lightness}%, 0.70)`,
      border: `hsla(${hue}, ${saturation}%, ${lightness - 10}%, 0.90)`
    };
  }

  function createDistribChart(){
    const ctxDistrib = document.getElementById('chartDistribuicao');
    if(!ctxDistrib){ console.warn('Canvas chartDistribuicao não encontrado'); return; }
    if(chartDistribInstance){ chartDistribInstance.destroy(); }
    const distColors = labelsDistrib.map(score => getScoreColor(score));
    chartDistribInstance = new Chart(ctxDistrib, {
      type: 'doughnut',
      data: {
        labels: labelsDistrib.map(l => l.toString()),
        datasets: [{
          label: 'Total respostas',
          data: valoresDistrib,
          backgroundColor: distColors.map(c => c.bg),
          borderColor: distColors.map(c => c.border),
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'bottom',
            labels: {
              generateLabels: chart => {
                const data = chart.data;
                if(!data.labels) return [];
                return data.labels.map((label, i) => ({
                  text: label,
                  fillStyle: distColors[i].bg,
                  strokeStyle: distColors[i].border,
                  hidden: false,
                  index: i
                }));
              }
            }
          },
          tooltip: {
            callbacks: {
              label: ctx => `Nota ${ctx.label}: ${ctx.parsed}`
            }
          }
        },
        cutout: '55%'
      }
    });
  }

  async function atualizarPorSetor(setorId){
    try {
      const url = setorId ? `${baseDadosUrl}/${setorId}` : baseDadosUrl;
      const resp = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' }});
      if(!resp.ok){ console.warn('Falha ao buscar dados'); return; }
      const data = await resp.json();
      fullLabelsMedias = data.labels || [];
      labelsMedias = fullLabelsMedias.map(l => truncarTexto(l));
      valoresMedias = data.averages || [];
      labelsDistrib = data.scoreLabels || [];
      valoresDistrib = data.scoreValues || [];
      createBarChart();
      createDistribChart();
    } catch(e){ console.error('Erro atualização setor', e); }
  }

  function bindFiltro(){
    const sel = document.getElementById('filtroSetor');
    if(!sel) return;
    sel.addEventListener('change', e => {
      const v = (e.target.value || '').trim();
      atualizarPorSetor(v !== '' ? v : null);
    });
  }

  // Inicialização
  bindFiltro();
  createBarChart();
  createDistribChart();
})();
