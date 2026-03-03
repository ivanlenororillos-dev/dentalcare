<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dental Chart Calibration Tool</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f3f4f6; }
        .toolbar {
            position: fixed; top: 0; left: 0; right: 0; z-index: 100;
            background: #1e293b; color: white; padding: 10px 20px;
            display: flex; align-items: center; justify-content: space-between;
            box-shadow: 0 2px 10px rgba(0,0,0,.3);
        }
        .toolbar h1 { font-size: 16px; font-weight: 600; }
        .toolbar .info { font-size: 12px; color: #94a3b8; }
        .toolbar button {
            padding: 8px 16px; border: none; border-radius: 6px;
            font-weight: 600; cursor: pointer; font-size: 13px;
        }
        .btn-export { background: #22c55e; color: white; }
        .btn-export:hover { background: #16a34a; }
        .btn-reset { background: #ef4444; color: white; margin-right: 8px; }
        .btn-reset:hover { background: #dc2626; }
        .btn-copy { background: #3b82f6; color: white; margin-right: 8px; }
        .btn-copy:hover { background: #2563eb; }

        .container { display: flex; margin-top: 50px; }
        .chart-area {
            flex: 1; display: flex; justify-content: center; align-items: flex-start;
            padding: 20px;
        }
        .svg-wrapper { position: relative; display: inline-block; }

        .panel {
            width: 360px; background: white; border-left: 1px solid #e2e8f0;
            height: calc(100vh - 50px); overflow-y: auto; padding: 16px;
            position: fixed; right: 0; top: 50px;
        }
        .panel h3 { font-size: 14px; font-weight: 600; margin-bottom: 12px; color: #334155; }
        .coord-output {
            width: 100%; height: calc(100vh - 130px);
            font-family: 'Consolas', 'Monaco', monospace; font-size: 11px;
            border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px;
            background: #f8fafc; resize: none; color: #334155;
        }

        .tooth-overlay {
            cursor: grab; transition: fill-opacity 0.1s;
        }
        .tooth-overlay:hover { fill-opacity: 0.7 !important; }
        .tooth-overlay.dragging { cursor: grabbing; fill-opacity: 0.8 !important; }
        .tooth-overlay.selected { stroke: #1a1a2e; stroke-width: 2.5; }

        .selected-info {
            position: fixed; bottom: 20px; left: 50%; transform: translateX(-50%);
            background: #1e293b; color: white; padding: 8px 20px; border-radius: 8px;
            font-size: 13px; z-index: 100; box-shadow: 0 4px 12px rgba(0,0,0,.3);
        }
        .copied-toast {
            position: fixed; top: 60px; left: 50%; transform: translateX(-50%);
            background: #22c55e; color: white; padding: 8px 20px; border-radius: 8px;
            font-size: 13px; z-index: 200; box-shadow: 0 4px 12px rgba(0,0,0,.2);
            display: none;
        }
    </style>
</head>
<body>
    <div class="toolbar">
        <div>
            <h1>🦷 Dental Chart Calibration Tool</h1>
            <div class="info">Drag rectangles to align them over the teeth. Use arrow keys for fine-tuning (1px). Hold Shift+Arrow for 5px steps.</div>
        </div>
        <div>
            <button class="btn-reset" onclick="resetPositions()">Reset All</button>
            <button class="btn-copy" onclick="copyPhpCode()">📋 Copy PHP Code</button>
            <button class="btn-export" onclick="exportCoords()">💾 Update & Apply</button>
        </div>
    </div>

    <div class="container">
        <div class="chart-area">
            <div class="svg-wrapper">
                <svg id="chart-svg" viewBox="0 0 403 672" xmlns="http://www.w3.org/2000/svg"
                     style="width: 500px; height: auto; max-height: 85vh;">
                    <image href="{{ asset('images/tooth-chart.png') }}" x="0" y="0" width="403" height="672" />
                </svg>
            </div>
        </div>
        <div class="panel">
            <h3>Generated PHP Coordinates</h3>
            <textarea id="coord-output" class="coord-output" readonly></textarea>
        </div>
    </div>

    <div id="selected-info" class="selected-info" style="display:none">
        Tooth #<span id="sel-num"></span> — x: <span id="sel-x"></span>, y: <span id="sel-y"></span>, w: <span id="sel-w"></span>, h: <span id="sel-h"></span>
        &nbsp;|&nbsp; Arrow keys: move 1px, Shift+Arrow: 5px, +/-: resize
    </div>

    <div id="copied-toast" class="copied-toast">✅ PHP code copied to clipboard!</div>

    <script>
        // Initial coordinate data from chart.blade.php
        const defaultTeeth = {
            1:  {x: 42,  y: 281, w: 28, h: 22},
            2:  {x: 41,  y: 247, w: 28, h: 22},
            3:  {x: 43,  y: 214, w: 28, h: 22},
            4:  {x: 54,  y: 181, w: 28, h: 22},
            5:  {x: 68,  y: 147, w: 28, h: 22},
            6:  {x: 74,  y: 104, w: 28, h: 22},
            7:  {x: 108, y: 78,  w: 28, h: 22},
            8:  {x: 160, y: 65,  w: 28, h: 22},
            9:  {x: 212, y: 66,  w: 28, h: 22},
            10: {x: 263, y: 78,  w: 28, h: 22},
            11: {x: 297, y: 104, w: 28, h: 22},
            12: {x: 304, y: 149, w: 28, h: 22},
            13: {x: 318, y: 182, w: 28, h: 22},
            14: {x: 328, y: 215, w: 28, h: 22},
            15: {x: 329, y: 248, w: 28, h: 22},
            16: {x: 333, y: 280, w: 28, h: 22},
            17: {x: 331, y: 401, w: 28, h: 22},
            18: {x: 333, y: 434, w: 28, h: 22},
            19: {x: 327, y: 470, w: 28, h: 22},
            20: {x: 320, y: 503, w: 28, h: 22},
            21: {x: 302, y: 533, w: 28, h: 22},
            22: {x: 296, y: 579, w: 28, h: 22},
            23: {x: 259, y: 604, w: 28, h: 22},
            24: {x: 211, y: 621, w: 28, h: 22},
            25: {x: 159, y: 621, w: 28, h: 22},
            26: {x: 114, y: 604, w: 28, h: 22},
            27: {x: 74,  y: 578, w: 28, h: 22},
            28: {x: 65,  y: 535, w: 28, h: 22},
            29: {x: 53,  y: 501, w: 28, h: 22},
            30: {x: 45,  y: 471, w: 28, h: 22},
            31: {x: 38,  y: 434, w: 28, h: 22},
            32: {x: 38,  y: 399, w: 28, h: 22},
        };

        // Working copy
        let teeth = JSON.parse(JSON.stringify(defaultTeeth));
        // Try to load saved state
        const saved = localStorage.getItem('dental_calibration');
        if (saved) {
            try { teeth = JSON.parse(saved); } catch(e) {}
        }

        let selectedTooth = null;
        let dragging = null;
        let dragStart = {x: 0, y: 0};
        let rectStart = {x: 0, y: 0};

        const svg = document.getElementById('chart-svg');
        const colors = [
            '#22c55e','#3b82f6','#f59e0b','#ef4444','#8b5cf6','#ec4899',
            '#06b6d4','#14b8a6','#f97316','#64748b','#a855f7','#e11d48'
        ];

        function getColor(num) {
            return colors[(num - 1) % colors.length];
        }

        function getSvgPoint(e) {
            const pt = svg.createSVGPoint();
            pt.x = e.clientX;
            pt.y = e.clientY;
            return pt.matrixTransform(svg.getScreenCTM().inverse());
        }

        function render() {
            // Remove existing overlays
            svg.querySelectorAll('.tooth-group').forEach(el => el.remove());

            for (const [num, pos] of Object.entries(teeth)) {
                const g = document.createElementNS('http://www.w3.org/2000/svg', 'g');
                g.classList.add('tooth-group');
                g.dataset.tooth = num;

                const rect = document.createElementNS('http://www.w3.org/2000/svg', 'rect');
                rect.setAttribute('x', pos.x);
                rect.setAttribute('y', pos.y);
                rect.setAttribute('width', pos.w);
                rect.setAttribute('height', pos.h);
                rect.setAttribute('rx', '6');
                rect.setAttribute('ry', '6');
                rect.setAttribute('fill', getColor(parseInt(num)));
                rect.setAttribute('fill-opacity', '0.5');
                rect.setAttribute('stroke', selectedTooth == num ? '#1a1a2e' : 'rgba(0,0,0,0.3)');
                rect.setAttribute('stroke-width', selectedTooth == num ? '2.5' : '0.5');
                rect.classList.add('tooth-overlay');
                if (selectedTooth == num) rect.classList.add('selected');

                const text = document.createElementNS('http://www.w3.org/2000/svg', 'text');
                text.setAttribute('x', pos.x + pos.w / 2);
                text.setAttribute('y', pos.y + pos.h / 2 + 1);
                text.setAttribute('text-anchor', 'middle');
                text.setAttribute('dominant-baseline', 'middle');
                text.setAttribute('font-size', '9');
                text.setAttribute('font-weight', 'bold');
                text.setAttribute('fill', '#fff');
                text.setAttribute('stroke', '#000');
                text.setAttribute('stroke-width', '0.4');
                text.setAttribute('paint-order', 'stroke');
                text.setAttribute('pointer-events', 'none');
                text.textContent = num;

                g.appendChild(rect);
                g.appendChild(text);
                svg.appendChild(g);

                // Drag events
                rect.addEventListener('mousedown', (e) => {
                    e.preventDefault();
                    dragging = parseInt(num);
                    selectedTooth = parseInt(num);
                    const pt = getSvgPoint(e);
                    dragStart = {x: pt.x, y: pt.y};
                    rectStart = {x: pos.x, y: pos.y};
                    rect.classList.add('dragging');
                    render();
                    updateInfo();
                });
            }
            updateOutput();
        }

        svg.addEventListener('mousemove', (e) => {
            if (dragging === null) return;
            const pt = getSvgPoint(e);
            const dx = pt.x - dragStart.x;
            const dy = pt.y - dragStart.y;
            teeth[dragging].x = Math.round(rectStart.x + dx);
            teeth[dragging].y = Math.round(rectStart.y + dy);
            render();
            updateInfo();
            save();
        });

        document.addEventListener('mouseup', () => {
            dragging = null;
        });

        // Keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (selectedTooth === null) return;
            const step = e.shiftKey ? 5 : 1;
            const t = teeth[selectedTooth];
            let handled = true;

            switch(e.key) {
                case 'ArrowLeft':  t.x -= step; break;
                case 'ArrowRight': t.x += step; break;
                case 'ArrowUp':    t.y -= step; break;
                case 'ArrowDown':  t.y += step; break;
                case '+': case '=': t.w += 1; t.h += 1; break;
                case '-': case '_': t.w = Math.max(10, t.w - 1); t.h = Math.max(10, t.h - 1); break;
                case 'Escape': selectedTooth = null; handled = true; break;
                default: handled = false;
            }
            if (handled) {
                e.preventDefault();
                render();
                updateInfo();
                save();
            }
        });

        function updateInfo() {
            const info = document.getElementById('selected-info');
            if (selectedTooth) {
                const t = teeth[selectedTooth];
                info.style.display = 'block';
                document.getElementById('sel-num').textContent = selectedTooth;
                document.getElementById('sel-x').textContent = t.x;
                document.getElementById('sel-y').textContent = t.y;
                document.getElementById('sel-w').textContent = t.w;
                document.getElementById('sel-h').textContent = t.h;
            } else {
                info.style.display = 'none';
            }
        }

        function generatePhpCode() {
            let lines = ['$allTeeth = ['];
            const sections = [
                {label: 'Upper Right Quadrant (1-8)', start: 1, end: 8},
                {label: 'Upper Left Quadrant (9-16)', start: 9, end: 16},
                {label: 'Lower Left Quadrant (17-24)', start: 17, end: 24},
                {label: 'Lower Right Quadrant (25-32)', start: 25, end: 32},
            ];
            for (const sec of sections) {
                lines.push(`    // ${sec.label}`);
                for (let i = sec.start; i <= sec.end; i++) {
                    const t = teeth[i];
                    const pad = i < 10 ? ' ' : '';
                    lines.push(`    ${pad}${i}  => ['x' => ${String(t.x).padStart(3)},  'y' => ${String(t.y).padStart(3)}, 'w' => ${t.w}, 'h' => ${t.h}],`);
                }
                if (sec.end < 32) lines.push('');
            }
            lines.push('];');
            return lines.join('\n');
        }

        function updateOutput() {
            document.getElementById('coord-output').value = generatePhpCode();
        }

        function save() {
            localStorage.setItem('dental_calibration', JSON.stringify(teeth));
        }

        function resetPositions() {
            if (!confirm('Reset all tooth positions to defaults?')) return;
            teeth = JSON.parse(JSON.stringify(defaultTeeth));
            selectedTooth = null;
            localStorage.removeItem('dental_calibration');
            render();
            updateInfo();
        }

        function copyPhpCode() {
            const code = generatePhpCode();
            navigator.clipboard.writeText(code).then(() => {
                const toast = document.getElementById('copied-toast');
                toast.style.display = 'block';
                setTimeout(() => { toast.style.display = 'none'; }, 2000);
            });
        }

        function exportCoords() {
            // Send coordinates to server to auto-update chart.blade.php
            const data = JSON.parse(JSON.stringify(teeth));
            fetch('{{ route("calibrate.save", $client) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({teeth: data}),
            }).then(r => r.json()).then(res => {
                if (res.success) {
                    alert('✅ Coordinates saved! The chart view has been updated. Go back to the chart to see the changes.');
                } else {
                    alert('Error: ' + (res.message || 'Unknown error'));
                }
            }).catch(err => {
                alert('Network error. The PHP code has been copied to clipboard instead — paste it manually into chart.blade.php.');
                copyPhpCode();
            });
        }

        // Initialize
        render();
    </script>
</body>
</html>
