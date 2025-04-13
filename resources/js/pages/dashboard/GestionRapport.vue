<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, type PageProps } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';
import RapportManagement from '@/components/RapportManagement.vue';
import Header from '@/components/Header.vue';
import Footer from '@/components/Footer.vue';
import { ref, onMounted, onBeforeUnmount } from 'vue';
import { Button } from '@/components/ui/button';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Rapports',
        href: '/dashboard/rapports',
    },
];

const { objets, categories, zones, pointsUtilisateurs } = usePage().props as unknown as PageProps;

// Fonction d'impression du rapport - approche utilisant iframe
const imprimerRapport = () => {
    // Calculer les statistiques pour le rapport
    const consommationTotale = objets.reduce((total, objet) => total + objet.consommationActuelle, 0);
    const puissanceTotale = objets.reduce((total, objet) => total + objet.puissance, 0);
    const objetsActifs = objets.filter(o => o.etat === 'Actif').length;
    const objetsInactifs = objets.filter(o => o.etat === 'Inactif').length;
    const objetsMaintenance = objets.filter(o => o.etat === 'Maintenance').length;

    // Calculer les objets par catégorie
    const categoriesMap = new Map();
    objets.forEach(objet => {
        const categorie = objet.categorie?.nom || 'Non catégorisé';
        categoriesMap.set(categorie, (categoriesMap.get(categorie) || 0) + 1);
    });
    const objetsParCategorie = Object.fromEntries(categoriesMap);

    // Date actuelle formatée
    const dateRapport = new Date().toLocaleDateString('fr-FR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    });

    // Création d'un iframe invisible
    const printIframe = document.createElement('iframe');
    printIframe.style.position = 'fixed';
    printIframe.style.right = '0';
    printIframe.style.bottom = '0';
    printIframe.style.width = '0';
    printIframe.style.height = '0';
    printIframe.style.border = '0';
    
    // Contenu HTML complet pour l'iframe
    const htmlContent = `
        <!DOCTYPE html>
        <html>
        <head>
            <title>Rapport de Consommation - ${dateRapport}</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    color: #333;
                    line-height: 1.5;
                    padding: 20px;
                    background-color: white;
                }
                .rapport-header {
                    text-align: center;
                    margin-bottom: 30px;
                    border-bottom: 2px solid #6366f1;
                    padding-bottom: 10px;
                }
                .stats-grid {
                    display: grid;
                    grid-template-columns: repeat(2, 1fr);
                    gap: 20px;
                    margin-bottom: 30px;
                }
                .stat-card {
                    border: 1px solid #d1d5db;
                    border-radius: 8px;
                    padding: 15px;
                    background-color: #f9fafb;
                }
                .stat-title {
                    font-size: 16px;
                    font-weight: bold;
                    color: #4f46e5;
                    margin-bottom: 5px;
                }
                .stat-value {
                    font-size: 24px;
                    font-weight: bold;
                }
                .categories-section {
                    margin-top: 30px;
                }
                .categories-grid {
                    display: grid;
                    grid-template-columns: repeat(3, 1fr);
                    gap: 15px;
                }
                .category-card {
                    border: 1px solid #d1d5db;
                    border-radius: 8px;
                    padding: 12px;
                    background-color: #f9fafb;
                }
                .section-title {
                    font-size: 18px;
                    font-weight: bold;
                    margin: 25px 0 15px 0;
                    color: #4f46e5;
                    border-bottom: 1px solid #e5e7eb;
                    padding-bottom: 5px;
                }
                .note {
                    font-style: italic;
                    color: #6b7280;
                    margin-top: 40px;
                }
                .footer {
                    margin-top: 50px;
                    text-align: center;
                    font-size: 12px;
                    color: #9ca3af;
                }
            </style>
        </head>
        <body>
            <div class="rapport-header">
                <h1>Rapport de Consommation des Objets Connectés</h1>
                <p>Date du rapport: ${dateRapport}</p>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-title">Consommation Totale</div>
                    <div class="stat-value">${consommationTotale}W</div>
                </div>
                <div class="stat-card">
                    <div class="stat-title">Puissance Totale</div>
                    <div class="stat-value">${puissanceTotale}W</div>
                </div>
                <div class="stat-card">
                    <div class="stat-title">Objets Actifs</div>
                    <div class="stat-value">${objetsActifs}</div>
                </div>
                <div class="stat-card">
                    <div class="stat-title">Objets en Maintenance</div>
                    <div class="stat-value">${objetsMaintenance}</div>
                </div>
            </div>

            <div class="section-title">Objets Inactifs</div>
            <p>Nombre d'objets inactifs: ${objetsInactifs}</p>

            <div class="categories-section">
                <div class="section-title">Distribution par catégorie</div>
                <div class="categories-grid">
                    ${Object.entries(objetsParCategorie).map(([categorie, count]) => `
                        <div class="category-card">
                            <div class="stat-title">${categorie}</div>
                            <div class="stat-value">${count}</div>
                        </div>
                    `).join('')}
                </div>
            </div>

            <div class="footer">
                © ${new Date().getFullYear()} - Rapport de consommation des objets connectés
            </div>
        </body>
        </html>
    `;

    // Ajout de l'iframe à la page
    document.body.appendChild(printIframe);
    
    // Écriture du contenu dans l'iframe et lancement de l'impression
    const iframeDocument = printIframe.contentDocument || printIframe.contentWindow?.document;
    if (iframeDocument) {
        iframeDocument.open();
        iframeDocument.write(htmlContent);
        iframeDocument.close();
        
        // Attendre que l'iframe soit chargé avant d'imprimer
        printIframe.onload = function() {
            setTimeout(() => {
                try {
                    printIframe.contentWindow?.print();
                    // Nettoyer l'iframe après l'impression
                    setTimeout(() => {
                        document.body.removeChild(printIframe);
                    }, 1000);
                } catch (e) {
                    console.error('Erreur lors de l\'impression:', e);
                    document.body.removeChild(printIframe);
                }
            }, 500);
        };
    }
};
</script>

<template>
    <Head title="Dashboard - Rapports" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <!-- Bouton d'impression -->
            <div class="flex justify-end mb-4">
                <Button @click="imprimerRapport" class="bg-indigo-600 hover:bg-indigo-700 text-white shadow-md">
                    Imprimer le rapport
                </Button>
            </div>
            
            <div class="relative overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                <RapportManagement
                    :objets="objets"
                    :categories="categories"
                    :zones="zones"
                    :pointsUtilisateurs="pointsUtilisateurs"
                />
            </div>
        </div>
    </AppLayout>
</template>