<div wire:keydown.escape.window="close">
    @if($isOpen)
        <div class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/70 backdrop-blur-sm"
             wire:click.self="close">
            <div @click.stop
                 x-data="{
                    display: '0',
                    expressionParts: [],
                    currentInput: '',
                    waitingForNewValue: false,
                    showAC: true,
                    showResult: false,

                    appendNumber(number) {
                      if (this.showResult) {
                        this.resetAfterResult();
                      }

                      if (this.waitingForNewValue) {
                        this.currentInput = '';
                        this.waitingForNewValue = false;
                      }

                      if (number === '.') {
                        if (this.currentInput === '') {
                          this.currentInput = '0.';
                        } else if (!this.currentInput.includes('.')) {
                          this.currentInput = this.currentInput + '.';
                        }
                      } else if (number === '00') {
                        if (this.currentInput !== '' && this.currentInput !== '0') {
                          this.currentInput = this.currentInput + '00';
                        } else if (this.currentInput === '') {
                          this.currentInput = '0';
                        }
                      } else {
                        if (this.currentInput === '0') {
                          this.currentInput = number.toString();
                        } else {
                          this.currentInput = this.currentInput + number;
                        }
                      }

                      if (this.currentInput === '') {
                        this.currentInput = number === '.' ? '0.' : number.toString();
                      }

                      this.showAC = false;
                      this.showResult = false;
                      this.updatePreviewDisplay();
                    },

                    resetAfterResult() {
                      this.expressionParts = [];
                      this.currentInput = '';
                      this.showResult = false;
                    },

                    setOperation(op) {
                      const symbols = ['+', '-', '*', '/'];

                      if (this.showResult) {
                        this.expressionParts = [this.display];
                        this.showResult = false;
                      }

                      if (this.currentInput !== '') {
                        this.expressionParts.push(this.currentInput);
                        this.currentInput = '';
                      } else if (this.expressionParts.length === 0) {
                        this.expressionParts.push(this.display);
                      }

                      const lastItem = this.expressionParts[this.expressionParts.length - 1];

                      if (symbols.includes(lastItem)) {
                        this.expressionParts[this.expressionParts.length - 1] = op;
                      } else {
                        this.expressionParts.push(op);
                      }

                      this.waitingForNewValue = true;
                      this.showAC = false;
                      this.showResult = false;
                      this.updatePreviewDisplay();
                    },

                    buildExpressionFromParts(parts) {
                      return parts.map((p, i) =>
                        i % 2 === 0 ? p : this.getOpSymbol(p)
                      ).join(' ');
                    },

                    getOpSymbol(op) {
                      const symbols = { '+': '+', '-': '−', '*': '×', '/': '÷' };
                      return symbols[op] || op;
                    },

                    getExpressionDisplay() {
                      if (this.showResult && this.expressionParts.length === 0 && this.currentInput === '') {
                        return '';
                      }

                      const parts = [...this.expressionParts];
                      if (this.currentInput !== '') {
                        parts.push(this.currentInput);
                      }

                      if (parts.length === 0) {
                        return '';
                      }

                      return this.buildExpressionFromParts(parts);
                    },

                    performCalculation() {
                      if (this.currentInput !== '') {
                        this.expressionParts.push(this.currentInput);
                        this.currentInput = '';
                      }

                      if (this.expressionParts.length === 0) return;

                      const lastItem = this.expressionParts[this.expressionParts.length - 1];
                      const symbols = ['+', '-', '*', '/'];

                      if (symbols.includes(lastItem)) {
                        this.expressionParts.pop();
                      }

                      if (this.expressionParts.length === 0) return;

                      const result = this.evaluateExpressionFromParts(this.expressionParts);
                      this.display = this.formatResult(result);
                      this.expressionParts = [];
                      this.currentInput = '';
                      this.waitingForNewValue = true;
                      this.showAC = true;
                      this.showResult = true;
                    },

                    evaluateExpressionFromParts(parts) {
                      const working = [...parts];

                      for (let i = 1; i < working.length; i += 2) {
                        if (working[i] === '*' || working[i] === '/') {
                          const left = parseFloat(working[i - 1]);
                          const rightIndex = i + 1;
                          if (rightIndex >= working.length) break;
                          const right = parseFloat(working[rightIndex]);
                          const value = working[i] === '*' ? left * right : (right !== 0 ? left / right : 0);
                          working.splice(i - 1, 3, value.toString());
                          i -= 2;
                        }
                      }

                      let result = parseFloat(working[0] || '0');
                      for (let i = 1; i < working.length; i += 2) {
                        const op = working[i];
                        const num = parseFloat(working[i + 1] || '0');
                        if (op === '+') result += num;
                        if (op === '-') result -= num;
                      }

                      return Math.round(result * 100000000) / 100000000;
                    },

                    updatePreviewDisplay() {
                      const parts = [...this.expressionParts];

                      if (this.currentInput !== '') {
                        parts.push(this.currentInput);
                      }

                      const cleaned = this.cleanParts(parts);

                      if (cleaned.length === 0) {
                        this.display = this.currentInput !== '' ? this.currentInput : '0';
                        return;
                      }

                      if (cleaned.length === 1) {
                        this.display = this.formatResult(parseFloat(cleaned[0] || '0'));
                        return;
                      }

                      const result = this.evaluateExpressionFromParts(cleaned);
                      this.display = this.formatResult(result);
                    },

                    cleanParts(parts) {
                      const symbols = ['+', '-', '*', '/'];
                      const cleaned = [...parts];
                      const lastItem = cleaned[cleaned.length - 1];
                      if (symbols.includes(lastItem)) {
                        cleaned.pop();
                      }
                      return cleaned;
                    },

                        formatResult(num) {
                      if (isNaN(num)) return '0';
                      let formatted = num.toString();
                      if (formatted.includes('.')) {
                        formatted = formatted.replace(/\.?0+$/, '');
                      }
                      if (formatted === '') {
                        formatted = '0';
                      }
                      if (formatted.length > 12) {
                        formatted = Number(num).toExponential(6);
                      }
                      return formatted;
                    },

                    allClear() {
                      this.display = '0';
                      this.expressionParts = [];
                      this.currentInput = '';
                      this.waitingForNewValue = false;
                      this.showAC = true;
                      this.showResult = false;
                    },

                    clearEntry() {
                      if (this.currentInput !== '') {
                        this.currentInput = '';
                        this.updatePreviewDisplay();
                      } else if (this.expressionParts.length > 0) {
                        this.expressionParts.pop();
                        this.updatePreviewDisplay();
                      } else {
                        this.allClear();
                      }
                    },

                    toggleSign() {
                      if (this.currentInput !== '') {
                        const value = parseFloat(this.currentInput) || 0;
                        this.currentInput = (-value).toString();
                        this.updatePreviewDisplay();
                      } else if (this.display !== '0') {
                        const value = parseFloat(this.display) || 0;
                        this.display = this.formatResult(-value);
                        this.currentInput = this.display;
                        this.updatePreviewDisplay();
                      }
                    },

                    percentage() {
                      if (this.currentInput !== '') {
                        const value = parseFloat(this.currentInput) || 0;
                        this.currentInput = (value / 100).toString();
                        this.updatePreviewDisplay();
                      } else if (this.display !== '0') {
                        const value = parseFloat(this.display) || 0;
                        this.display = this.formatResult(value / 100);
                      }
                    }
                 }"
                 class="bg-black text-white rounded-3xl shadow-[0_20px_45px_rgba(0,0,0,0.6)] border border-neutral-800 p-6 w-full max-w-sm relative z-[10000]">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold tracking-wide">{{ __('messages.calculator') }}</h3>
                    <button type="button" wire:click="close" class="text-gray-400 hover:text-white text-2xl font-light transition-colors cursor-pointer leading-none">&times;</button>
                </div>

                <div class="mb-6 bg-neutral-900 border border-neutral-800 rounded-2xl p-4 shadow-inner">
                    <div x-show="getExpressionDisplay()"
                         class="text-right text-sm text-gray-400 mb-2 min-h-[20px] font-light"
                         x-text="getExpressionDisplay()"></div>
                    <input type="text"
                           x-model="display"
                           readonly
                           class="w-full text-right text-4xl font-light bg-transparent text-white focus:outline-none"
                           style="font-family: -apple-system, BlinkMacSystemFont, 'SF Pro Display', sans-serif;">
                </div>

                <div class="grid grid-cols-4 gap-3">
                    <template x-if="showAC">
                        <button @click="allClear()" class="h-16 rounded-2xl transition-all active:scale-95 flex items-center justify-center shadow-lg bg-gradient-to-br from-gray-500/80 to-gray-700/80 text-white text-xl font-semibold backdrop-blur-sm border border-white/10">AC</button>
                    </template>
                    <template x-if="!showAC">
                        <button @click="clearEntry()" class="h-16 rounded-2xl transition-all active:scale-95 flex items-center justify-center shadow-lg bg-gradient-to-br from-gray-500/80 to-gray-700/80 text-white text-xl font-semibold backdrop-blur-sm border border-white/10">C</button>
                    </template>
                    <button @click="toggleSign()" class="h-16 rounded-2xl transition-all active:scale-95 flex items-center justify-center shadow-lg bg-gradient-to-br from-gray-500/80 to-gray-700/80 text-white text-xl font-semibold backdrop-blur-sm border border-white/10">±</button>
                    <button @click="percentage()" class="h-16 rounded-2xl transition-all active:scale-95 flex items-center justify-center shadow-lg bg-gradient-to-br from-gray-500/80 to-gray-700/80 text-white text-xl font-semibold backdrop-blur-sm border border-white/10">%</button>
                    <button @click="setOperation('/')" class="h-16 rounded-2xl transition-all active:scale-95 flex items-center justify-center shadow-lg bg-gradient-to-br from-amber-500 to-orange-600 hover:from-amber-400 hover:to-orange-500 text-white text-xl font-semibold">÷</button>

                    <button @click="appendNumber(7)" class="h-16 rounded-2xl transition-all active:scale-95 flex items-center justify-center shadow-lg bg-gradient-to-br from-gray-800 to-gray-900 hover:from-gray-700 hover:to-gray-800 text-white text-xl font-semibold border border-white/5">7</button>
                    <button @click="appendNumber(8)" class="h-16 rounded-2xl transition-all active:scale-95 flex items-center justify-center shadow-lg bg-gradient-to-br from-gray-800 to-gray-900 hover:from-gray-7  00 hover:to-gray-800 text-white text-xl font-semibold border border-white/5">8</button>
                    <button @click="appendNumber(9)" class="h-16 rounded-2xl transition-all active:scale-95 flex items-center justify-center shadow-lg bg-gradient-to-br from-gray-800 to-gray-900 hover:from-gray-700 hover:to-gray-800 text-white text-xl font-semibold border border-white/5">9</button>
                    <button @click="setOperation('*')" class="h-16 rounded-2xl transition-all active:scale-95 flex items-center justify-center shadow-lg bg-gradient-to-br from-amber-500 to-orange-600 hover:from-amber-400 hover:to-orange-500 text-white text-xl font-semibold">×</button>

                    <button @click="appendNumber(4)" class="h-16 rounded-2xl transition-all active:scale-95 flex items-center justify-center shadow-lg bg-gradient-to-br from-gray-800 to-gray-900 hover:from-gray-700 hover:to-gray-800 text-white text-xl font-semibold border border-white/5">4</button>
                    <button @click="appendNumber(5)" class="h-16 rounded-2xl transition-all active:scale-95 flex items-center justify-center shadow-lg bg-gradient-to-br from-gray-800 to-gray-900 hover:from-gray-700 hover:to-gray-800 text-white text-xl font-semibold border border-white/5">5</button>
                    <button @click="appendNumber(6)" class="h-16 rounded-2xl transition-all active:scale-95 flex items-center justify-center shadow-lg bg-gradient-to-br from-gray-800 to-gray-900 hover:from-gray-700 hover:to-gray-800 text-white text-xl font-semibold border border-white/5">6</button>
                    <button @click="setOperation('-')" class="h-16 rounded-2xl transition-all active:scale-95 flex items-center justify-center shadow-lg bg-gradient-to-br from-amber-500 to-orange-600 hover:from-amber-400 hover:to-orange-500 text-white text-xl font-semibold">−</button>

                    <button @click="appendNumber(1)" class="h-16 rounded-2xl transition-all active:scale-95 flex items-center justify-center shadow-lg bg-gradient-to-br from-gray-800 to-gray-900 hover:from-gray-700 hover:to-gray-800 text-white text-xl font-semibold border border-white/5">1</button>
                    <button @click="appendNumber(2)" class="h-16 rounded-2xl transition-all active:scale-95 flex items-center justify-center shadow-lg bg-gradient-to-br from-gray-800 to-gray-900 hover:from-gray-700 hover:to-gray-800 text-white text-xl font-semibold border border-white/5">2</button>
                    <button @click="appendNumber(3)" class="h-16 rounded-2xl transition-all active:scale-95 flex items-center justify-center shadow-lg bg-gradient-to-br from-gray-800 to-gray-900 hover:from-gray-700 hover:to-gray-800 text-white text-xl font-semibold border border-white/5">3</button>
                    <button @click="setOperation('+')" class="h-16 rounded-2xl transition-all active:scale-95 flex items-center justify-center shadow-lg bg-gradient-to-br from-amber-500 to-orange-600 hover:from-amber-400 hover:to-orange-500 text-white text-xl font-semibold">+</button>

                    <button @click="appendNumber(0)" class="h-16 rounded-2xl transition-all active:scale-95 flex items-center justify-center shadow-lg bg-gradient-to-br from-gray-800 to-gray-900 hover:from-gray-700 hover:to-gray-800 text-white text-xl font-semibold border border-white/5">0</button>
                    <button @click="appendNumber('00')" class="h-16 rounded-2xl transition-all active:scale-95 flex items-center justify-center shadow-lg bg-gradient-to-br from-gray-800 to-gray-900 hover:from-gray-700 hover:to-gray-800 text-white text-lg font-semibold border border-white/5">00</button>
                    <button @click="appendNumber('.')" class="h-16 rounded-2xl transition-all active:scale-95 flex items-center justify-center shadow-lg bg-gradient-to-br from-gray-800 to-gray-900 hover:from-gray-700 hover:to-gray-800 text-white text-xl font-semibold border border-white/5">.</button>
                    <button @click="performCalculation()" class="h-16 rounded-2xl transition-all active:scale-95 flex items-center justify-center shadow-lg bg-gradient-to-br from-emerald-500 to-teal-500 hover:from-emerald-400 hover:to-teal-400 text-white text-xl font-semibold">=</button>
                </div>
            </div>
        </div>
    @endif
</div>
