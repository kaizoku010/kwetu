class CurrencyConverter {
    constructor() {
        this.rates = {
            UGX: 1,
            USD: 1/3800,
            EUR: 1/4500,  // Example rate
            GBP: 1/5200   // Example rate
        };
        
        this.currentCurrency = 'UGX';
        this.setupEventListeners();
    }

    setupEventListeners() {
        const selector = document.getElementById('globalCurrencySelector');
        if (selector) {
            selector.addEventListener('change', (e) => {
                this.currentCurrency = e.target.value;
                this.updateAllPrices();
                // Save preference to localStorage
                localStorage.setItem('preferredCurrency', this.currentCurrency);
            });

            // Load saved preference
            const savedCurrency = localStorage.getItem('preferredCurrency');
            if (savedCurrency) {
                selector.value = savedCurrency;
                this.currentCurrency = savedCurrency;
                this.updateAllPrices();
            }
        }
    }

    convert(amount, fromCurrency, toCurrency) {
        // Convert to UGX first (base currency)
        const inUGX = amount / this.rates[fromCurrency];
        // Then convert to target currency
        return inUGX * this.rates[toCurrency];
    }

    formatPrice(amount, currency) {
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: currency
        }).format(amount);
    }

    updateAllPrices() {
        // Update all price elements with data-original-price attribute
        document.querySelectorAll('[data-original-price]').forEach(element => {
            const originalPrice = parseFloat(element.dataset.originalPrice);
            const originalCurrency = element.dataset.originalCurrency || 'UGX';
            const convertedAmount = this.convert(originalPrice, originalCurrency, this.currentCurrency);
            element.textContent = this.formatPrice(convertedAmount, this.currentCurrency);
        });
    }
}

// Initialize the converter
const currencyConverter = new CurrencyConverter();