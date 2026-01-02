import './bootstrap';
import Alpine from 'alpinejs';
import Chart from 'chart.js/auto';

// Alpine.js
window.Alpine = Alpine;
Alpine.start();

// Chart.js - Configuration globale avec palette TerangaMbay
Chart.defaults.font.family = 'Inter, system-ui, sans-serif';
Chart.defaults.color = '#4E342E'; // Brun Sillon

window.Chart = Chart;

// Couleurs pour les graphiques
window.chartColors = {
    mbay: '#2E7D32',
    teranga: '#F9A825',
    sillon: '#4E342E',
    nuage: '#F1F8E9',
    // Variations
    mbayLight: '#81C784',
    terangaLight: '#FFD54F',
    // Pour les graphiques multi-couleurs
    palette: [
        '#2E7D32', // Vert Mbay
        '#F9A825', // Or Teranga
        '#4E342E', // Brun Sillon
        '#66BB6A', // Vert clair
        '#FFCA28', // Or clair
        '#8D6E63', // Brun clair
        '#43A047', // Vert moyen
        '#FFB300', // Or moyen
    ]
};