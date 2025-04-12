<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';
import Header from '@/components/Header.vue';
import Footer from '@/components/Footer.vue';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { onMounted, ref } from 'vue';
import { useToast } from '@/components/ui/toast/use-toast';

const { toast } = useToast();
const isProcessing = ref(false);

const props = defineProps<{
    evenement: {
        id: number;
        nom: string;
        dateEvenements: string;
        lieu: string;
        typeEvents: string;
        meteo: string;
        ligue: string;
        descriptionEvenements: string;
        consignes_securite: string;
        activites_autour: string;
        equipeDomicile: string;
        equipeExterieur: string;
        logo_equipe_domicile: string;
        logo_equipe_exterieur: string;
        resultat: string;
        prix: number;
        Disponiblilite: number;
    };
}>();

// Coordonnées des stades (à adapter selon vos besoins)
const stadeCoordinates: { [key: string]: [number, number] } = {
    'Parc des Princes': [48.841389, 2.253056],
    'Stade de France': [48.924444, 2.360833],
    'Allianz Riviera': [43.7049, 7.19243],
    'Orange Vélodrome': [43.269722, 5.395833],
    'Groupama Stadium': [45.765278, 4.982222],
    'Stade Pierre-Mauroy': [50.611944, 3.130278],
    'Stade Geoffroy-Guichard': [45.458889, 4.387778],
    'Stade de la Beaujoire': [47.255833, -1.524722],
    'Stade de la Mosson': [43.622222, 3.813889],
    'Stade de la Meinau': [48.559722, 7.753889]
};

// Adresses des stades
const stadeAdresses: { [key: string]: string } = {
    'Parc des Princes': '24 Rue du Commandant Guilbaud, 75016 Paris',
    'Stade de France': 'Stade de France, 93200 Saint-Denis',
    'Allianz Riviera': 'Boulevard des Jardiniers, 06200 Nice',
    'Orange Vélodrome': '3 Boulevard Michelet, 13008 Marseille',
    'Groupama Stadium': '10 Avenue Simone Veil, 69150 Décines-Charpieu',
    'Stade Pierre-Mauroy': '261 Boulevard de Tournai, 59650 Villeneuve-d\'Ascq',
    'Stade Geoffroy-Guichard': '14 Rue Paul et Pierre Guichard, 42000 Saint-Étienne',
    'Stade de la Beaujoire': 'Route de Saint-Joseph, 44300 Nantes',
    'Stade de la Mosson': 'Avenue de Heidelberg, 34000 Montpellier',
    'Stade de la Meinau': '12 Rue de l\'Avenir, 67100 Strasbourg'
};

// Mapping des noms d'équipes vers les noms de fichiers de logos
const logoMapping: { [key: string]: string } = {
    'Paris Saint-Germain': 'Paris_Saint-Germain_Logo.png',
    'Manchester City': 'Manchester_City_Logo.png',
    'Olympique de Marseille': 'Logo_Olympique_de_Marseille.png',
    'OGC Nice': 'Logo_OGC_Nice.png'
};

// Fonction pour obtenir le chemin du logo
const getLogoPath = (equipe: { nom: string; logo?: string }): string => {
    if (equipe.logo) return `/images/logos/${equipe.logo}`;
    const mappedLogo = logoMapping[equipe.nom];
    return mappedLogo ? `/images/logos/${mappedLogo}` : '';
};

// Fonction pour extraire le lieu à partir des informations de transport
const extractLieuFromDescription = (description: string): string | null => {
    // Recherche de "Saint-Denis" dans la description
    if (description.includes('Saint-Denis')) {
        return 'Stade de France';
    }
    
    // Recherche d'autres stades connus dans la description
    const stades = Object.keys(stadeAdresses);
    for (const stade of stades) {
        if (description.includes(stade)) {
            return stade;
        }
    }
    
    return null;
};

// Fonction pour obtenir le lieu à afficher
const getLieu = (): string => {
    // Si le lieu est défini, l'utiliser
    if (props.evenement.lieu) {
        return props.evenement.lieu;
    }
    
    // Sinon, essayer de l'extraire de la description
    if (props.evenement.descriptionEvenements) {
        const lieuExtrait = extractLieuFromDescription(props.evenement.descriptionEvenements);
        if (lieuExtrait) {
            return lieuExtrait;
        }
    }
    
    // Si rien n'est trouvé, retourner Stade de France par défaut
    return 'Stade de France';
};

// Fonction pour obtenir l'adresse du stade
const getAdresse = (lieu: string): string => {
    // Si le lieu est vide ou non défini, essayer de l'extraire de la description
    if (!lieu && props.evenement.descriptionEvenements) {
        const lieuExtrait = extractLieuFromDescription(props.evenement.descriptionEvenements);
        if (lieuExtrait) {
            return stadeAdresses[lieuExtrait] || 'Adresse non disponible';
        }
    }
    
    // Si le lieu est vide ou non défini, retourner l'adresse du Stade de France par défaut
    if (!lieu) return stadeAdresses['Stade de France'] || 'Adresse non disponible';
    
    // Vérifier si le lieu existe dans le dictionnaire
    const adresse = stadeAdresses[lieu];
    if (adresse) return adresse;
    
    // Si le lieu n'est pas trouvé, essayer de trouver une correspondance partielle
    const lieuLower = lieu.toLowerCase();
    for (const [key, value] of Object.entries(stadeAdresses)) {
        if (key.toLowerCase().includes(lieuLower) || lieuLower.includes(key.toLowerCase())) {
            return value;
        }
    }
    
    // Si aucune correspondance n'est trouvée, retourner l'adresse du Stade de France par défaut
    return stadeAdresses['Stade de France'] || 'Adresse non disponible';
};

// Fonction pour obtenir le score en fonction du résultat
const getScore = (resultat: string, equipe: 'domicile' | 'exterieur'): string => {
    if (!resultat) return '-';
    
    if (resultat === 'victoire') {
        return equipe === 'domicile' ? '2' : '0';
    } else if (resultat === 'defaite') {
        return equipe === 'domicile' ? '0' : '2';
    } else if (resultat === 'nul') {
        return '1';
    }
    
    return '-';
};

onMounted(() => {
    // Chargement de Leaflet depuis CDN
    const link = document.createElement('link');
    link.rel = 'stylesheet';
    link.href = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css';
    document.head.appendChild(link);

    const script = document.createElement('script');
    script.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
    script.onload = () => initMap();
    document.head.appendChild(script);
});

const initMap = () => {
    // Coordonnées par défaut du Stade de France
    const defaultCoordinates = stadeCoordinates['Stade de France'] || [48.924444, 2.360833];
    
    // Utiliser les coordonnées du lieu de l'événement ou les coordonnées par défaut
    const coordinates = props.evenement.lieu && stadeCoordinates[props.evenement.lieu] 
        ? stadeCoordinates[props.evenement.lieu] 
        : defaultCoordinates;
    
    // Nom du lieu à afficher sur la carte
    const lieuName = props.evenement.lieu || 'Stade de France';
    
    const map = L.map('map').setView(coordinates, 14);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    L.marker(coordinates)
        .addTo(map)
        .bindPopup(lieuName)
        .openPopup();
};

// Fonction pour gérer les erreurs de chargement d'image
const handleImageError = (event: Event) => {
    const img = event.target as HTMLImageElement;
    img.style.display = 'none';
    const parent = img.parentElement;
    if (parent) {
        const fallback = document.createElement('div');
        fallback.className = 'h-32 w-32 flex items-center justify-center bg-white/10 rounded-full';
        fallback.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-[#E4F1F1]/40" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
            </svg>
        `;
        parent.appendChild(fallback);
    }
};

const formatDate = (dateString: string) => {
    if (!dateString) return 'Date non disponible';
    const date = new Date(dateString);
    if (isNaN(date.getTime())) return 'Date non disponible';
    return date.toLocaleDateString('fr-FR', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};

const acheterTicket = () => {
    if (isProcessing.value) return;
    
    if (props.evenement.Disponiblilite <= 0) {
        toast({
            title: "Impossible d'acheter un ticket",
            description: "Désolé, il n'y a plus de places disponibles pour cet événement.",
            variant: "destructive"
        });
        return;
    }
    
    isProcessing.value = true;
    
    router.post('/tickets', {
        idEvenements: props.evenement.id,
        typePlace: 'Standard',
        notes: `Ticket acheté pour l'événement ${props.evenement.nom}`
    }, {
        onSuccess: () => {
            toast({
                title: "Ticket acheté avec succès",
                description: "Votre ticket a été généré et est disponible dans votre espace personnel.",
                variant: "default"
            });
        },
        onError: (errors) => {
            toast({
                title: "Erreur lors de l'achat du ticket",
                description: "Une erreur est survenue lors de l'achat de votre ticket. Veuillez réessayer.",
                variant: "destructive"
            });
        },
        onFinish: () => {
            isProcessing.value = false;
        }
    });
};
</script>

<template>
    <div class="min-h-screen flex flex-col bg-gradient-to-tr from-indigo-900 to-gray-900">
        <Header />
        <main class="flex-grow py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <!-- Bouton retour -->
                <div class="mb-6">
                    <Link 
                        :href="route('evenements.index')" 
                        class="text-white hover:text-cyan-300 transition flex items-center gap-2"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                        </svg>
                        Retour aux événements
                    </Link>
                </div>

                <!-- Titre de la page -->
                <div class="mb-8 text-center">
                    <h1 class="text-4xl font-bold text-white tracking-[0.05em] mb-2">{{ evenement.nom }}</h1>
                    <p class="text-white/80 tracking-[0.05em]">
                        {{ formatDate(evenement.dateEvenements) }}
                    </p>
                </div>

                <!-- Bloc 1: Informations principales -->
                <div class="mb-6 overflow-hidden rounded-lg border border-indigo-500/30 bg-gray-900 bg-opacity-90 shadow-md backdrop-blur-sm">
                    <div class="p-6">
                        <h3 class="text-xl font-medium text-white tracking-[0.05em] mb-4">Informations principales</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div class="flex items-center gap-2 text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                    </svg>
                                    <span>{{ getLieu() }}</span>
                                </div>

                                <div class="flex items-center gap-2 text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" />
                                    </svg>
                                    <span>{{ evenement.typeEvents }} {{ evenement.ligue }}</span>
                                </div>

                                <div v-if="evenement.meteo" class="flex items-center gap-2 text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M5.5 16a3.5 3.5 0 01-.369-6.98 4 4 0 117.753-1.977A4.5 4.5 0 1113.5 16h-8z" />
                                    </svg>
                                    <span>{{ evenement.meteo }}</span>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div class="flex items-center gap-2 text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z" />
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd" />
                                    </svg>
                                    <span>Prix : {{ evenement.prix }}€</span>
                                </div>

                                <div class="flex items-center gap-2 text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
                                    </svg>
                                    <span>Places disponibles : {{ evenement.Disponiblilite }}</span>
                                </div>
                                
                                <div class="mt-4">
                                    <Button 
                                        @click="acheterTicket" 
                                        :disabled="isProcessing || evenement.Disponiblilite <= 0"
                                        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg transition-all duration-300 flex items-center justify-center gap-2"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z" />
                                            <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z" />
                                        </svg>
                                        <span v-if="isProcessing">Traitement en cours...</span>
                                        <span v-else-if="evenement.Disponiblilite <= 0">Plus de places disponibles</span>
                                        <span v-else>Acheter un ticket</span>
                                    </Button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bloc 2: Équipes et résultat -->
                <Card class="bg-white/10 backdrop-blur-sm border-none mb-6">
                    <CardHeader>
                        <CardTitle class="text-xl font-semibold text-[#E4F1F1]">Équipes</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="flex items-center justify-center gap-8 text-center">
                            <div class="flex-1">
                                <div class="bg-white/5 p-4 rounded-lg">
                                    <div class="flex justify-center mb-4">
                                        <img 
                                            :src="`/images/logos/${evenement.logo_equipe_domicile}`"
                                            :alt="evenement.equipeDomicile"
                                            class="h-32 w-32 object-contain"
                                            @error="handleImageError"
                                        />
                                    </div>
                                    <div class="text-lg font-medium text-[#E4F1F1]">{{ evenement.equipeDomicile }}</div>
                                    <div class="text-sm text-[#E4F1F1]/60">Domicile</div>
                                </div>
                            </div>
                            <div class="text-2xl font-bold text-[#E4F1F1]">VS</div>
                            <div class="flex-1">
                                <div class="bg-white/5 p-4 rounded-lg">
                                    <div class="flex justify-center mb-4">
                                        <img 
                                            :src="`/images/logos/${evenement.logo_equipe_exterieur}`"
                                            :alt="evenement.equipeExterieur"
                                            class="h-32 w-32 object-contain"
                                            @error="handleImageError"
                                        />
                                    </div>
                                    <div class="text-lg font-medium text-[#E4F1F1]">{{ evenement.equipeExterieur }}</div>
                                    <div class="text-sm text-[#E4F1F1]/60">Extérieur</div>
                                </div>
                            </div>
                        </div>

                        <!-- Résultat avec statistiques -->
                        <div v-if="evenement.resultat" class="mt-8">
                            <h3 class="text-xl font-semibold mb-4 text-[#E4F1F1] text-center">Résultat</h3>
                            <div class="flex justify-center items-center gap-4">
                                <div class="flex flex-col items-center">
                                    <div class="text-2xl font-bold text-[#E4F1F1]">{{ evenement.equipeDomicile }}</div>
                                    <div class="text-4xl font-bold" 
                                         :class="{
                                            'text-green-400': evenement.resultat === 'victoire',
                                            'text-red-400': evenement.resultat === 'defaite',
                                            'text-gray-400': evenement.resultat === 'nul'
                                         }">
                                        {{ getScore(evenement.resultat, 'domicile') }}
                                    </div>
                                </div>
                                <div class="text-2xl font-bold text-[#E4F1F1]">-</div>
                                <div class="flex flex-col items-center">
                                    <div class="text-2xl font-bold text-[#E4F1F1]">{{ evenement.equipeExterieur }}</div>
                                    <div class="text-4xl font-bold" 
                                         :class="{
                                            'text-green-400': evenement.resultat === 'defaite',
                                            'text-red-400': evenement.resultat === 'victoire',
                                            'text-gray-400': evenement.resultat === 'nul'
                                         }">
                                        {{ getScore(evenement.resultat, 'exterieur') }}
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4 text-center">
                                <div class="inline-flex items-center px-4 py-2 rounded-full" 
                                     :class="{
                                        'bg-green-500/20 text-green-400': evenement.resultat === 'victoire',
                                        'bg-red-500/20 text-red-400': evenement.resultat === 'defaite',
                                        'bg-gray-500/20 text-gray-400': evenement.resultat === 'nul'
                                     }">
                                    {{ evenement.resultat.charAt(0).toUpperCase() + evenement.resultat.slice(1) }}
                                </div>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Bloc 4: Description et détails -->
                <Card class="bg-white/10 backdrop-blur-sm border-none mb-6">
                    <CardHeader>
                        <CardTitle class="text-xl font-semibold text-[#E4F1F1]">Description et détails</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-4">
                            <div class="bg-white/5 p-4 rounded-lg">
                                <h4 class="text-lg font-medium mb-2 text-[#E4F1F1]">Description de l'événement</h4>
                                <p class="text-[#E4F1F1]/80">{{ evenement.descriptionEvenements }}</p>
                            </div>
                            <div class="bg-white/5 p-4 rounded-lg">
                                <h4 class="text-lg font-medium mb-2 text-[#E4F1F1]">Activités autour du stade</h4>
                                <p class="text-[#E4F1F1]/80">{{ evenement.activites_autour || 'Aucune activité spécifique prévue autour du stade.' }}</p>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Bloc 5: Accès et transport -->
                <Card class="bg-white/10 backdrop-blur-sm border-none mb-6">
                    <CardHeader>
                        <CardTitle class="text-xl font-semibold text-[#E4F1F1]">Accès et transport</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="grid gap-4 md:grid-cols-2">
                            <div class="bg-white/5 p-4 rounded-lg">
                                <h4 class="text-lg font-medium mb-2 text-[#E4F1F1]">En voiture</h4>
                                <p class="text-[#E4F1F1]/80">Parking disponible sur place. Tarifs et réservation sur notre site.</p>
                            </div>
                            <div class="bg-white/5 p-4 rounded-lg">
                                <h4 class="text-lg font-medium mb-2 text-[#E4F1F1]">En transports en commun</h4>
                                <p class="text-[#E4F1F1]/80">Métro ligne 13, station Saint-Denis - Porte de Paris. RER D, station Stade de France - Saint-Denis.</p>
                            </div>
                            <div class="bg-white/5 p-4 rounded-lg">
                                <h4 class="text-lg font-medium mb-2 text-[#E4F1F1]">À pied</h4>
                                <p class="text-[#E4F1F1]/80">10 minutes depuis la station de métro Saint-Denis - Porte de Paris.</p>
                            </div>
                            <div class="bg-white/5 p-4 rounded-lg">
                                <h4 class="text-lg font-medium mb-2 text-[#E4F1F1]">En vélo</h4>
                                <p class="text-[#E4F1F1]/80">Parking vélo sécurisé disponible. Station Vélib' à proximité.</p>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Bloc 6: Sécurité et consignes -->
                <Card class="bg-white/10 backdrop-blur-sm border-none mb-6">
                    <CardHeader>
                        <CardTitle class="text-xl font-semibold text-[#E4F1F1]">Sécurité et consignes</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-4">
                            <div class="bg-white/5 p-4 rounded-lg">
                                <h4 class="text-lg font-medium mb-2 text-[#E4F1F1]">Consignes de sécurité</h4>
                                <p class="text-[#E4F1F1]/80">{{ evenement.consignes_securite || 'Aucune consigne de sécurité spécifique pour cet événement.' }}</p>
                            </div>
                            <div class="bg-white/5 p-4 rounded-lg">
                                <h4 class="text-lg font-medium mb-2 text-[#E4F1F1]">Objets interdits</h4>
                                <ul class="list-inside list-disc text-[#E4F1F1]/80">
                                    <li>Objets tranchants ou contondants</li>
                                    <li>Bouteilles en verre</li>
                                    <li>Objets pyrotechniques</li>
                                    <li>Banderoles ou drapeaux avec manche</li>
                                </ul>
                            </div>
                            <div class="bg-white/5 p-4 rounded-lg">
                                <h4 class="text-lg font-medium mb-2 text-[#E4F1F1]">Recommandations</h4>
                                <ul class="list-inside list-disc text-[#E4F1F1]/80">
                                    <li>Arrivez au moins 1h avant le début de l'événement</li>
                                    <li>Présentez votre billet sur votre smartphone ou imprimé</li>
                                    <li>Respectez les consignes des agents de sécurité</li>
                                    <li>En cas d'urgence, contactez le personnel de sécurité</li>
                                </ul>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Bloc 7: Localisation -->
                <Card class="bg-white/10 backdrop-blur-sm border-none">
                    <CardHeader>
                        <CardTitle class="text-xl font-semibold text-[#E4F1F1]">Localisation</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="flex flex-col md:flex-row gap-6">
                            <div class="md:w-1/3">
                                <div class="bg-white/5 p-4 rounded-lg h-full">
                                    <h3 class="text-lg font-medium mb-2 text-[#E4F1F1]">Adresse</h3>
                                    <p class="text-[#E4F1F1]/80 mb-4">{{ evenement.lieu }}</p>
                                    <h3 class="text-lg font-medium mb-2 text-[#E4F1F1]">Horaires d'ouverture</h3>
                                    <p class="text-[#E4F1F1]/80">Portes ouvertes 2h avant le début de l'événement</p>
                                </div>
                            </div>
                            <div class="md:w-2/3">
                                <div id="map" class="h-[400px] w-full rounded-lg overflow-hidden"></div>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </main>
        <Footer />
    </div>
</template>

<style>
.leaflet-container {
    background-color: rgba(255, 255, 255, 0.1);
}
</style>
