<?php
$config = require 'config.php';
?>
<!DOCTYPE html>
<html lang="en" class="dark h-full">
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
        html, body { height: 100%; margin: 0; padding: 0; overflow: hidden; }
        
        .custom-scroll {
            scrollbar-width: thin;
            scrollbar-color: #3f3f46 transparent;
        }
        
        .custom-scroll::-webkit-scrollbar {
            width: 10px;
            height: 10px;
        }
        
        .custom-scroll::-webkit-scrollbar-track {
            background: transparent;
        }
        
        .custom-scroll::-webkit-scrollbar-thumb {
            background-color: #27272a;
            border: 2px solid transparent;
            background-clip: content-box;
            border-radius: 9999px;
        }
        
        .custom-scroll::-webkit-scrollbar-thumb:hover {
            background-color: #52525b;
        }
        
        .custom-scroll::-webkit-scrollbar-corner {
            background: transparent;
        }

        textarea { 
            resize: none; 
            outline: none;
            overscroll-behavior: none;
        }
        
        ::selection {
            background-color: rgba(99, 102, 241, 0.25);
            color: #e0e7ff;
        }
    </style>
</head>
<body class="bg-zinc-950 text-zinc-300 font-sans flex flex-col h-full selection:bg-indigo-500/30 selection:text-indigo-200">

    <!-- Header -->
    <header class="h-14 border-b border-zinc-800 bg-zinc-950 flex items-center justify-between px-4 sm:px-6 shrink-0 z-20 relative">
        <div class="flex items-center gap-3">
            <img src="https://github.com/MTEXdotDev.png" alt="MTEX" class="w-7 h-7 rounded-md bg-zinc-800 opacity-80">
            <h1 class="font-semibold tracking-tight text-zinc-100 text-sm md:text-base"><?= $config['APP_NAME'] ?></h1>
            <span class="text-[10px] px-1.5 py-0.5 rounded border border-zinc-800 bg-zinc-900 text-zinc-500 font-mono">v<?= $config['APP_VERSION'] ?></span>
        </div>
        <div class="flex items-center gap-4">
            <a href="<?= $config['GITHUB_URL'] ?>" target="_blank" class="text-zinc-600 hover:text-zinc-300 transition-colors">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd"/></svg>
            </a>
        </div>
    </header>

    <!-- Toolbar -->
    <div class="h-12 border-b border-zinc-800 bg-zinc-950 flex items-center justify-between px-4 sm:px-6 shrink-0 z-20">
        <div class="flex items-center gap-2">
            <button onclick="prettify()" class="h-8 px-3 text-xs font-medium bg-zinc-900 hover:bg-zinc-800 hover:text-white border border-zinc-800 rounded transition text-zinc-400 flex items-center gap-2">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
                Format
            </button>
            <button onclick="swapContent()" class="h-8 px-3 text-xs font-medium bg-zinc-900 hover:bg-zinc-800 hover:text-white border border-zinc-800 rounded transition text-zinc-400 flex items-center gap-2">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                Swap
            </button>
            <button onclick="clearInputs()" class="h-8 px-3 text-xs font-medium hover:bg-red-500/10 hover:text-red-400 border border-transparent hover:border-red-500/20 rounded transition text-zinc-500 flex items-center gap-2">
                Clear
            </button>
        </div>
        
        <div class="bg-zinc-900 p-0.5 rounded-md border border-zinc-800 flex">
            <button id="btn-split" onclick="setViewMode('split')" class="px-3 py-1 text-[11px] font-medium rounded shadow-sm transition-all duration-200">Split</button>
            <button id="btn-unified" onclick="setViewMode('unified')" class="px-3 py-1 text-[11px] font-medium rounded transition-all duration-200 text-zinc-500 hover:text-zinc-300">Unified</button>
        </div>
    </div>

    <!-- Main Content Area -->
    <main class="flex-1 relative flex flex-col min-h-0">
        
        <!-- Input Mode -->
        <div id="input-container" class="absolute inset-0 grid grid-cols-1 md:grid-cols-2 divide-y md:divide-y-0 md:divide-x divide-zinc-800 bg-zinc-950 z-10">
            
            <!-- Left Input -->
            <div class="flex flex-col min-h-0 bg-zinc-950">
                <div class="h-8 shrink-0 bg-zinc-950 border-b border-zinc-800/50 flex items-center px-4 justify-between text-[11px] font-mono text-zinc-500 uppercase tracking-wider select-none">
                    <span class="flex items-center gap-2"><div class="w-1.5 h-1.5 rounded-full bg-zinc-700"></div>Original</span>
                    <span id="char-count-left" class="opacity-50">0 chars</span>
                </div>
                <div class="flex-1 relative min-h-0">
                    <textarea id="input-left" spellcheck="false" placeholder="Paste original content here..." 
                        class="absolute inset-0 w-full h-full bg-zinc-950 p-4 font-mono text-[13px] leading-6 text-zinc-300 custom-scroll focus:bg-zinc-900/30 transition-colors pb-20"></textarea>
                </div>
            </div>

            <!-- Right Input -->
            <div class="flex flex-col min-h-0 bg-zinc-950">
                <div class="h-8 shrink-0 bg-zinc-950 border-b border-zinc-800/50 flex items-center px-4 justify-between text-[11px] font-mono text-zinc-500 uppercase tracking-wider select-none">
                    <span class="flex items-center gap-2"><div class="w-1.5 h-1.5 rounded-full bg-indigo-500/50"></div>Modified</span>
                    <span id="char-count-right" class="opacity-50">0 chars</span>
                </div>
                <div class="flex-1 relative min-h-0">
                    <textarea id="input-right" spellcheck="false" placeholder="Paste modified content here..." 
                        class="absolute inset-0 w-full h-full bg-zinc-950 p-4 font-mono text-[13px] leading-6 text-zinc-300 custom-scroll focus:bg-zinc-900/30 transition-colors pb-20"></textarea>
                </div>
            </div>
        </div>

        <!-- Diff Result Mode -->
        <div id="diff-container" class="absolute inset-0 z-0 hidden flex-col bg-zinc-950">
            <div class="h-9 shrink-0 bg-zinc-900/50 border-b border-zinc-800 flex items-center justify-between px-4 backdrop-blur-sm">
                <span class="text-xs text-zinc-400 font-medium">Comparison Result</span>
                <button onclick="toggleEditMode()" class="text-xs text-indigo-400 hover:text-indigo-300 font-medium flex items-center gap-1 hover:underline underline-offset-4">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path></svg>
                    Back to Edit
                </button>
            </div>
            
            <!-- Split Diff View -->
            <div id="diff-output-split" class="flex-1 min-h-0 grid grid-cols-2 divide-x divide-zinc-800 font-mono text-[13px]">
                <div id="split-left" class="custom-scroll overflow-y-auto overflow-x-auto bg-zinc-950"></div>
                <div id="split-right" class="custom-scroll overflow-y-auto overflow-x-auto bg-zinc-950"></div>
            </div>
            
            <!-- Unified Diff View -->
            <div id="diff-output-unified" class="flex-1 min-h-0 custom-scroll overflow-y-auto font-mono text-[13px] bg-zinc-950 hidden"></div>
        </div>
        
        <!-- Floating Action Button -->
        <div id="action-bar" class="absolute bottom-6 left-1/2 -translate-x-1/2 z-30 transition-all duration-300 opacity-0 translate-y-4 pointer-events-none">
            <button onclick="computeDiff()" class="shadow-2xl shadow-black ring-1 ring-white/10 bg-indigo-600 hover:bg-indigo-500 text-white px-6 py-2.5 rounded-full text-sm font-medium flex items-center gap-2 transition transform active:scale-95 group">
                <span>Compare</span>
                <svg class="w-4 h-4 text-indigo-200 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </button>
        </div>
    </main>

    <!-- Footer -->
    <footer class="h-7 bg-zinc-950 border-t border-zinc-800 shrink-0 flex items-center justify-between px-4 text-[10px] text-zinc-600 select-none z-20">
        <span>Part of the MTEX.dev ecosystem</span>
        <div class="flex gap-4">
            <a href="#" class="hover:text-zinc-400 transition">Imprint</a>
            <a href="#" class="hover:text-zinc-400 transition">Privacy</a>
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
            const hasC = els.inL.value.length > 0 || els.inR.value.length > 0;
            
            const fmt = n => n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            
            els.actBar.classList.toggle('opacity-100', hasC);
            els.actBar.classList.toggle('translate-y-0', hasC);
            els.actBar.classList.toggle('pointer-events-auto', hasC);
            
            els.cntL.textContent = `${fmt(els.inL.value.length)} chars`;
            els.cntR.textContent = `${fmt(els.inR.value.length)} chars`;
        };

        els.inL.addEventListener('input', handleInput);
        els.inR.addEventListener('input', handleInput);

        const sync = (source, target) => { 
            if(mode === 'diff' && view === 'split') { 
                target.scrollTop = source.scrollTop; 
                target.scrollLeft = source.scrollLeft; 
            }
        };
        els.splL.addEventListener('scroll', () => sync(els.splL, els.splR));
        els.splR.addEventListener('scroll', () => sync(els.splR, els.splL));

        function swapContent() {
            const temp = els.inL.value;
            els.inL.value = els.inR.value;
            els.inR.value = temp;
            handleInput();
            if(mode === 'diff') computeDiff();
        }

        function clearInputs() {
            els.inL.value = els.inR.value = '';
            handleInput();
            if(mode === 'diff') toggleEditMode();
        }

        function prettify() {
            const fmt = e => { 
                try { 
                    if(e.value.trim()) e.value = JSON.stringify(JSON.parse(e.value), null, 4); 
                } catch(x){
                } 
            };
            fmt(els.inL); fmt(els.inR);
            handleInput();
            if(mode === 'diff') computeDiff();
        }

        function setViewMode(m) {
            view = m;
            const act = ['bg-zinc-700', 'text-white'], inact = ['text-zinc-500', 'hover:text-zinc-300'];
            const isS = m === 'split';
            
            els.btnSpl.className = `px-3 py-1 text-[11px] font-medium rounded shadow-sm transition-all duration-200 ${isS ? 'bg-zinc-600 text-white' : 'text-zinc-500 hover:text-zinc-300'}`;
            els.btnUni.className = `px-3 py-1 text-[11px] font-medium rounded transition-all duration-200 ${!isS ? 'bg-zinc-600 text-white shadow-sm' : 'text-zinc-500 hover:text-zinc-300'}`;

            if(isS) {
                els.outSpl.classList.remove('hidden');
                els.outSpl.classList.add('grid');
                els.outUni.classList.add('hidden');
            } else {
                els.outSpl.classList.add('hidden');
                els.outSpl.classList.remove('grid');
                els.outUni.classList.remove('hidden');
            }
        }

        function toggleEditMode() {
            mode = 'input';
            els.inCont.classList.remove('hidden');
            els.diffCont.classList.add('hidden');
            els.diffCont.classList.remove('flex');
            
            handleInput();
        }

        function computeDiff() {
            mode = 'diff';
            els.inCont.classList.add('hidden');
            els.diffCont.classList.remove('hidden');
            els.diffCont.classList.add('flex');
            els.actBar.classList.remove('opacity-100', 'translate-y-0', 'pointer-events-auto');
            els.actBar.classList.add('opacity-0', 'translate-y-4', 'pointer-events-none');

            const diff = Diff.diffLines(els.inL.value, els.inR.value);
            renderSplit(diff);
            renderUnified(diff);
            
            els.splL.scrollTop = 0;
            els.splR.scrollTop = 0;
            els.outUni.scrollTop = 0;
        }

        const esc = s => s.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;");

        function renderSplit(d) {
            let lH = '', rH = '', lL = 1, rL = 1;
            
            d.forEach(p => {
                const lines = p.value.replace(/\n$/, '').split('\n');
                lines.forEach(l => {
                    const s = esc(l) || ' ';
                    if (p.removed) {
                        lH += row(lL++, s, 'bg-red-500/10 text-red-200', 'bg-red-500/20');
                        rH += row('', '', 'bg-zinc-950/40 opacity-30', 'transparent');
                    } else if (p.added) {
                        lH += row('', '', 'bg-zinc-950/40 opacity-30', 'transparent');
                        rH += row(rL++, s, 'bg-green-500/10 text-green-200', 'bg-green-500/20');
                    } else {
                        lH += row(lL++, s, 'text-zinc-400 hover:bg-zinc-900', 'border-zinc-800/50');
                        rH += row(rL++, s, 'text-zinc-400 hover:bg-zinc-900', 'border-zinc-800/50');
                    }
                });
            });
            
            const pad = '<div class="h-20 bg-zinc-950 w-full"></div>';
            els.splL.innerHTML = `<div class="w-full font-mono text-[13px]">${lH}</div>${pad}`;
            els.splR.innerHTML = `<div class="w-full font-mono text-[13px]">${rH}</div>${pad}`;
        }

        function renderUnified(d) {
            let h = '', oL = 1, nL = 1;
            d.forEach(p => {
                const lines = p.value.replace(/\n$/, '').split('\n');
                lines.forEach(l => {
                    if (p.added) {
                        h += uRow(oL, nL++, '+', esc(l), 'bg-green-500/10 text-green-200');
                    } else if (p.removed) {
                        h += uRow(oL++, nL, '-', esc(l), 'bg-red-500/10 text-red-200');
                    } else {
                        h += uRow(oL++, nL++, ' ', esc(l), 'text-zinc-400 hover:bg-zinc-900/50');
                    }
                });
            });
            els.outUni.innerHTML = `<div class="w-full pb-20">${h}</div>`;
        }

        const row = (n, c, cl, gutterCl) => `
            <div class="flex ${cl} w-full min-h-[24px]">
                <div class="w-10 shrink-0 select-none text-zinc-600 text-[10px] leading-6 pr-2 text-right border-r ${gutterCl || 'border-zinc-800/50'} bg-zinc-900/30 pt-[1px] font-mono opacity-70">${n}</div>
                <div class="pl-3 py-0.5 whitespace-pre break-all w-full leading-6">${c}</div>
            </div>`;
        
        const uRow = (o, n, m, c, cl) => {
            const oN = m==='+'?'':o, nN = m==='-'?'':n;
            return `
            <div class="flex ${cl} w-full min-h-[24px]">
                <div class="w-10 shrink-0 select-none text-zinc-600 text-[10px] leading-6 pr-2 text-right border-r border-zinc-800/50 bg-zinc-900/30 pt-[1px] opacity-70">${oN}</div>
                <div class="w-10 shrink-0 select-none text-zinc-600 text-[10px] leading-6 pr-2 text-right border-r border-zinc-800/50 bg-zinc-900/30 pt-[1px] opacity-70">${nN}</div>
                <div class="w-6 shrink-0 select-none text-zinc-500 text-center leading-6 opacity-40 text-[10px] font-bold">${m}</div>
                <div class="py-0.5 whitespace-pre break-all w-full leading-6">${c}</div>
            </div>`;
        };

        if(window.innerWidth < 768) setViewMode('unified');
    </script>
</body>
</html>