<?php
$config = require 'config.php';
?>
<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title><?= $config['APP_NAME'] ?> // Visual JSON & Code Diff Tool</title>
    <meta name="title" content="<?= $config['APP_NAME'] ?> // Visual JSON & Code Diff Tool">
    <meta name="description" content="A high-performance visual comparison tool for JSON payloads and code snippets. Features split and unified views, JSON prettification, and real-time diffing.">
    <meta name="keywords" content="JSON Diff, Code Compare, Visual Diff, Prettify JSON, Developer Tools, MTEX, Diff Checker">
    <meta name="author" content="MTEXdotDev">

    <meta property="og:type" content="website">
    <meta property="og:url" content="https://diff.mtex.dev/">
    <meta property="og:title" content="<?= $config['APP_NAME'] ?> // Code Comparison">
    <meta property="og:description" content="Compare JSON and code snippets instantly with split or unified views.">
    <meta property="og:image" content="https://github.com/MTEXdotDev.png">

    <meta property="twitter:card" content="summary">
    <meta property="twitter:url" content="https://diff.mtex.dev/">
    <meta property="twitter:title" content="<?= $config['APP_NAME'] ?> // Diff Tool">
    <meta property="twitter:description" content="A lightweight, developer-first visual comparison tool for JSON and code.">
    <meta property="twitter:image" content="https://github.com/MTEXdotDev.png">

    <link rel="icon" type="image/x-icon" href="https://github.com/MTEXdotDev.png">
    <link rel="apple-touch-icon" href="https://github.com/MTEXdotDev.png">
    <meta name="theme-color" content="#09090b">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jsdiff/5.1.0/diff.min.js"></script>

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { 
                        sans: ['Inter', 'sans-serif'], 
                        mono: ['JetBrains Mono', 'monospace'] 
                    },
                    colors: { 
                        zinc: { 850: '#1f2023', 900: '#18181b', 950: '#09090b' } 
                    }
                }
            }
        }
    </script>

    <style>
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #09090b; }
        ::-webkit-scrollbar-thumb { background: #27272a; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #3f3f46; }

        textarea { field-sizing: content; }
        
        ::selection {
            background-color: rgba(99, 102, 241, 0.3);
            color: #e0e7ff;
        }
        
        html { -webkit-text-size-adjust: 100%; }
    </style>
</head>
<body class="bg-zinc-950 text-zinc-300 font-sans h-screen flex flex-col overflow-hidden selection:bg-indigo-500/30 selection:text-indigo-200">

    <!-- Header -->
    <header class="h-14 border-b border-zinc-800 bg-zinc-950 flex items-center justify-between px-4 sm:px-6 shrink-0 z-10">
        <div class="flex items-center gap-3">
            <img src="https://github.com/MTEXdotDev.png" alt="MTEX" class="w-8 h-8 rounded-md bg-zinc-800">
            <h1 class="font-semibold tracking-tight text-white"><?= $config['APP_NAME'] ?></h1>
            <span class="text-xs px-2 py-0.5 rounded-full bg-zinc-800 text-zinc-400 border border-zinc-700">v<?= $config['APP_VERSION'] ?></span>
        </div>
        <div class="flex items-center gap-4">
            <a href="<?= $config['GITHUB_URL'] ?>" target="_blank" class="text-zinc-500 hover:text-white transition">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd"/></svg>
            </a>
        </div>
    </header>

    <!-- Controls -->
    <div class="h-14 border-b border-zinc-800 bg-zinc-900/50 flex items-center justify-between px-4 sm:px-6 shrink-0 overflow-x-auto">
        <div class="flex items-center gap-2">
            <button onclick="prettify()" class="group flex items-center gap-2 px-3 py-1.5 text-xs font-medium bg-zinc-800 hover:bg-zinc-700 border border-zinc-700 rounded transition text-zinc-300">
                <svg class="w-4 h-4 text-zinc-400 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>
                Prettify JSON
            </button>
            <button onclick="swapContent()" class="group flex items-center gap-2 px-3 py-1.5 text-xs font-medium bg-zinc-800 hover:bg-zinc-700 border border-zinc-700 rounded transition text-zinc-300">
                <svg class="w-4 h-4 text-zinc-400 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                Swap
            </button>
            <button onclick="clearInputs()" class="group flex items-center gap-2 px-3 py-1.5 text-xs font-medium hover:bg-red-900/20 hover:text-red-400 hover:border-red-900/50 border border-transparent rounded transition text-zinc-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                Clear
            </button>
        </div>
        <div class="flex items-center gap-2 bg-zinc-800 p-1 rounded-md border border-zinc-700">
            <button id="btn-split" onclick="setViewMode('split')" class="px-3 py-1 text-xs font-medium rounded bg-zinc-600 text-white shadow-sm transition">Split</button>
            <button id="btn-unified" onclick="setViewMode('unified')" class="px-3 py-1 text-xs font-medium rounded text-zinc-400 hover:text-white transition">Unified</button>
        </div>
    </div>

    <!-- Main -->
    <main class="flex-1 flex flex-col relative overflow-hidden">
        
        <!-- Editors -->
        <div id="input-container" class="absolute inset-0 grid grid-cols-1 md:grid-cols-2 divide-y md:divide-y-0 md:divide-x divide-zinc-800 bg-zinc-950 z-20">
            <div class="flex flex-col h-full">
                <div class="h-8 bg-zinc-900/30 border-b border-zinc-800 flex items-center px-4 justify-between text-xs font-mono text-zinc-500">
                    <span>ORIGINAL</span>
                    <span id="char-count-left">0 chars</span>
                </div>
                <textarea id="input-left" spellcheck="false" placeholder="Paste original..." class="flex-1 w-full bg-zinc-950 p-4 font-mono text-sm text-zinc-300 resize-none outline-none focus:bg-zinc-900/30 transition-colors"></textarea>
            </div>
            <div class="flex flex-col h-full">
                <div class="h-8 bg-zinc-900/30 border-b border-zinc-800 flex items-center px-4 justify-between text-xs font-mono text-zinc-500">
                    <span>MODIFIED</span>
                    <span id="char-count-right">0 chars</span>
                </div>
                <textarea id="input-right" spellcheck="false" placeholder="Paste modified..." class="flex-1 w-full bg-zinc-950 p-4 font-mono text-sm text-zinc-300 resize-none outline-none focus:bg-zinc-900/30 transition-colors"></textarea>
            </div>
        </div>

        <!-- Diff View -->
        <div id="diff-container" class="absolute inset-0 z-10 hidden flex-col">
            <div class="h-8 bg-zinc-900 border-b border-zinc-800 flex items-center justify-between px-4">
                <span class="text-xs text-zinc-400">Result</span>
                <button onclick="toggleEditMode()" class="text-xs text-indigo-400 hover:text-indigo-300 font-mono hover:underline">← Edit</button>
            </div>
            <div id="diff-output-split" class="flex-1 grid grid-cols-2 divide-x divide-zinc-800 overflow-y-auto font-mono text-sm">
                <div id="split-left" class="bg-zinc-950/50"></div>
                <div id="split-right" class="bg-zinc-950/50"></div>
            </div>
            <div id="diff-output-unified" class="flex-1 overflow-y-auto font-mono text-sm bg-zinc-950 hidden"></div>
        </div>
        
        <!-- Compare Btn -->
        <div id="action-bar" class="absolute bottom-10 left-1/2 -translate-x-1/2 z-30 transition-all duration-300 opacity-0 translate-y-4 pointer-events-none">
            <button onclick="computeDiff()" class="shadow-xl shadow-black/50 bg-white text-zinc-950 hover:bg-zinc-200 px-6 py-2.5 rounded-full text-sm font-semibold flex items-center gap-2 transition transform active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Compare Content
            </button>
        </div>
    </main>

    <!-- Footer -->
    <footer class="h-8 bg-zinc-950 border-t border-zinc-800 shrink-0 flex items-center justify-between px-6 text-[10px] text-zinc-600">
        <span>Part of the MTEX.dev ecosystem</span>
        <div class="flex gap-4">
            <a href="https://legal.mtex.dev/imprint" class="hover:text-zinc-400 transition">Imprint</a>
            <a href="https://legal.mtex.dev/privacy" class="hover:text-zinc-400 transition">Privacy</a>
        </div>
    </footer>

    <script>
        const els = {
            inCont: document.getElementById('input-container'),
            diffCont: document.getElementById('diff-container'),
            inL: document.getElementById('input-left'),
            inR: document.getElementById('input-right'),
            splL: document.getElementById('split-left'),
            splR: document.getElementById('split-right'),
            outSpl: document.getElementById('diff-output-split'),
            outUni: document.getElementById('diff-output-unified'),
            actBar: document.getElementById('action-bar'),
            btnSpl: document.getElementById('btn-split'),
            btnUni: document.getElementById('btn-unified'),
            cntL: document.getElementById('char-count-left'),
            cntR: document.getElementById('char-count-right')
        };

        let mode = 'input', view = 'split';

        const handleInput = () => {
            const hasC = els.inL.value.trim() || els.inR.value.trim();
            els.actBar.classList.toggle('opacity-100', !!hasC);
            els.actBar.classList.toggle('translate-y-0', !!hasC);
            els.actBar.classList.toggle('pointer-events-auto', !!hasC);
            els.cntL.textContent = `${els.inL.value.length} chars`;
            els.cntR.textContent = `${els.inR.value.length} chars`;
        };

        els.inL.addEventListener('input', handleInput);
        els.inR.addEventListener('input', handleInput);

        const sync = (s, t) => { if(mode === 'diff' && view === 'split') { t.scrollTop = s.scrollTop; t.scrollLeft = s.scrollLeft; }};
        els.splL.addEventListener('scroll', () => sync(els.splL, els.splR));
        els.splR.addEventListener('scroll', () => sync(els.splR, els.splL));

        function swapContent() {
            [els.inL.value, els.inR.value] = [els.inR.value, els.inL.value];
            handleInput();
            if(mode === 'diff') computeDiff();
        }

        function clearInputs() {
            els.inL.value = els.inR.value = '';
            handleInput();
            toggleEditMode();
        }

        function prettify() {
            const fmt = e => { try { if(e.value.trim()) e.value = JSON.stringify(JSON.parse(e.value), null, 2); } catch(x){} };
            fmt(els.inL); fmt(els.inR);
            handleInput();
            if(mode === 'diff') computeDiff();
        }

        function setViewMode(m) {
            view = m;
            const act = ['bg-zinc-600', 'text-white'], inact = ['text-zinc-400', 'hover:text-white'];
            const isS = m === 'split';
            
            els.btnSpl.classList[isS ? 'add':'remove'](...act);
            els.btnSpl.classList[isS ? 'remove':'add'](...inact);
            els.btnUni.classList[!isS ? 'add':'remove'](...act);
            els.btnUni.classList[!isS ? 'remove':'add'](...inact);

            els.outSpl.classList[isS ? 'remove':'add']('hidden');
            els.outSpl.classList[isS ? 'add':'remove']('grid');
            els.outUni.classList[isS ? 'add':'remove']('hidden');
        }

        function toggleEditMode() {
            mode = 'input';
            els.inCont.classList.remove('hidden');
            els.diffCont.classList.add('hidden');
            els.diffCont.classList.remove('flex');
            els.actBar.classList.remove('hidden');
        }

        function computeDiff() {
            mode = 'diff';
            els.inCont.classList.add('hidden');
            els.diffCont.classList.remove('hidden');
            els.diffCont.classList.add('flex');
            els.actBar.classList.add('hidden');

            const diff = Diff.diffLines(els.inL.value, els.inR.value);
            renderSplit(diff);
            renderUnified(diff);
        }

        const esc = s => s.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;");

        function renderSplit(d) {
            let lH = '', rH = '', lL = 1, rL = 1;
            d.forEach(p => {
                p.value.replace(/\n$/, '').split('\n').forEach(l => {
                    const s = esc(l) || '&nbsp;';
                    if (p.removed) {
                        lH += row(lL++, s, 'bg-red-900/20 text-red-300');
                        rH += row('', '', 'bg-zinc-950/30');
                    } else if (p.added) {
                        lH += row('', '', 'bg-zinc-950/30');
                        rH += row(rL++, s, 'bg-green-900/20 text-green-300');
                    } else {
                        lH += row(lL++, s, 'hover:bg-zinc-900');
                        rH += row(rL++, s, 'hover:bg-zinc-900');
                    }
                });
            });
            els.splL.innerHTML = `<div class="w-full pb-10">${lH}</div>`;
            els.splR.innerHTML = `<div class="w-full pb-10">${rH}</div>`;
        }

        function renderUnified(d) {
            let h = '', oL = 1, nL = 1;
            d.forEach(p => {
                p.value.replace(/\n$/, '').split('\n').forEach(l => {
                    if (p.added) h += uRow(oL, nL++, '+', esc(l), 'bg-green-900/20 text-green-300');
                    else if (p.removed) h += uRow(oL++, nL, '-', esc(l), 'bg-red-900/20 text-red-300');
                    else h += uRow(oL++, nL++, ' ', esc(l), 'text-zinc-400 hover:bg-zinc-900');
                });
            });
            els.outUni.innerHTML = `<div class="w-full pb-10">${h}</div>`;
        }

        const row = (n, c, cl) => `<div class="flex ${cl} w-full"><div class="w-12 shrink-0 select-none text-zinc-600 text-[10px] leading-6 pr-3 text-right border-r border-zinc-800/50 bg-zinc-950/20 pt-[1px] font-mono">${n}</div><div class="pl-3 py-0.5 whitespace-pre leading-5 break-all font-mono text-sm w-full">${c}</div></div>`;
        
        const uRow = (o, n, m, c, cl) => {
            const oN = m==='+'?'':o, nN = m==='-'?'':n;
            return `<div class="flex ${cl} w-full"><div class="w-10 shrink-0 select-none text-zinc-600 text-[10px] leading-6 pr-2 text-right border-r border-zinc-800/50 bg-zinc-950/20 pt-[1px]">${oN}</div><div class="w-10 shrink-0 select-none text-zinc-600 text-[10px] leading-6 pr-2 text-right border-r border-zinc-800/50 bg-zinc-950/20 pt-[1px]">${nN}</div><div class="w-6 shrink-0 select-none text-zinc-500 text-center leading-6 opacity-50">${m}</div><div class="py-0.5 whitespace-pre leading-5 break-all font-mono text-sm w-full">${c}</div></div>`;
        };

        if(window.innerWidth < 768) setViewMode('unified');
    </script>
</body>
</html>