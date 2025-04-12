<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, type PageProps } from '@/types';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { useToast } from '@/components/ui/toast/use-toast';
import { router } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Tickets',
        href: '/dashboard/tickets',
    },
    {
        title: 'Détails du ticket',
        href: '#',
    },
];

const { ticket } = usePage().props as unknown as PageProps;
const { toast } = useToast();

const formatDate = (dateString: string): string => {
    const date = new Date(dateString);
    return date.toLocaleDateString('fr-FR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};

const getStatusBadgeClass = (status: string): string => {
    switch (status) {
        case 'valide':
            return 'bg-green-500/20 text-green-500 hover:bg-green-500/30';
        case 'utilise':
            return 'bg-blue-500/20 text-blue-500 hover:bg-blue-500/30';
        case 'annule':
            return 'bg-red-500/20 text-red-500 hover:bg-red-500/30';
        default:
            return 'bg-gray-500/20 text-gray-500 hover:bg-gray-500/30';
    }
};

const getStatusLabel = (status: string): string => {
    switch (status) {
        case 'valide':
            return 'Valide';
        case 'utilise':
            return 'Utilisé';
        case 'annule':
            return 'Annulé';
        default:
            return status.charAt(0).toUpperCase() + status.slice(1);
    }
};

const cancelTicket = () => {
    if (!confirm('Êtes-vous sûr de vouloir annuler ce ticket ?')) return;

    router.post(`/tickets/${ticket.idTicket}/cancel`, {}, {
        onSuccess: () => {
            router.visit('/dashboard/tickets', { preserveScroll: true });
            toast({
                title: "Succès",
                description: "Ticket annulé avec succès",
                variant: "default"
            });
        },
        onError: () => {
            toast({
                title: "Erreur",
                description: "Impossible d'annuler le ticket",
                variant: "destructive"
            });
        }
    });
};
</script>

<template>
    <Head title="Dashboard - Détails du ticket" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <!-- Bouton retour -->
            <div class="mb-6">
                <Link 
                    :href="route('tickets.index')" 
                    class="text-white hover:text-cyan-300 transition flex items-center gap-2"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                    Retour aux tickets
                </Link>
            </div>

            <div class="grid gap-6">
                <!-- En-tête du ticket -->
                <Card class="bg-white/10 backdrop-blur-sm border-none">
                    <CardHeader>
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                            <div>
                                <CardTitle class="text-2xl font-bold text-white tracking-[0.05em]">{{ ticket.evenement.nom }}</CardTitle>
                                <p class="text-white/70 mt-1">Ticket #{{ ticket.numeroTicket }}</p>
                            </div>
                            <Badge :class="getStatusBadgeClass(ticket.statut)" class="text-lg px-4 py-1">
                                {{ getStatusLabel(ticket.statut) }}
                            </Badge>
                        </div>
                    </CardHeader>
                </Card>

                <!-- Informations principales -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Informations de l'événement -->
                    <Card class="bg-white/10 backdrop-blur-sm border-none">
                        <CardHeader>
                            <CardTitle class="text-xl font-semibold text-[#E4F1F1]">Informations de l'événement</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div class="space-y-4">
                                <div class="flex items-center gap-2 text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                    </svg>
                                    <span>{{ formatDate(ticket.evenement.dateEvenements) }}</span>
                                </div>
                                <div class="flex items-center gap-2 text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                    </svg>
                                    <span>{{ ticket.evenement.lieu }}</span>
                                </div>
                                <div class="flex items-center gap-2 text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
                                    </svg>
                                    <span>{{ ticket.evenement.typeEvents }}</span>
                                </div>
                                <div class="flex items-center gap-2 text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
                                    </svg>
                                    <span>{{ ticket.evenement.equipeDomicile }} vs {{ ticket.evenement.equipeExterieur }}</span>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Informations du ticket -->
                    <Card class="bg-white/10 backdrop-blur-sm border-none">
                        <CardHeader>
                            <CardTitle class="text-xl font-semibold text-[#E4F1F1]">Informations du ticket</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div class="space-y-4">
                                <div class="flex items-center gap-2 text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                    </svg>
                                    <span>{{ ticket.typePlace }} {{ ticket.numeroPlace ? `- ${ticket.numeroPlace}` : '' }}</span>
                                </div>
                                <div class="flex items-center gap-2 text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z" />
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd" />
                                    </svg>
                                    <span>{{ ticket.prixPaye }}€</span>
                                </div>
                                <div class="flex items-center gap-2 text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                    </svg>
                                    <span>Acheté le {{ formatDate(ticket.dateAchat) }}</span>
                                </div>
                                <div v-if="ticket.dateUtilisation" class="flex items-center gap-2 text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                    </svg>
                                    <span>Utilisé le {{ formatDate(ticket.dateUtilisation) }}</span>
                                </div>
                                <div class="flex items-center gap-2 text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    <span>Statut :</span>
                                    <Badge :class="getStatusBadgeClass(ticket.statut)">
                                        {{ getStatusLabel(ticket.statut) }}
                                    </Badge>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </div>

                <!-- Notes -->
                <Card v-if="ticket.notes" class="bg-white/10 backdrop-blur-sm border-none">
                    <CardHeader>
                        <CardTitle class="text-xl font-semibold text-[#E4F1F1]">Notes</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <p class="text-white">{{ ticket.notes }}</p>
                    </CardContent>
                </Card>

                <!-- Actions -->
                <div class="flex justify-end gap-4">
                    <Button 
                        v-if="ticket.statut === 'valide'"
                        @click="cancelTicket"
                        class="bg-red-900/30 hover:bg-red-800/40 border border-red-500/30 hover:border-red-400/50 text-white hover:text-red-300"
                    >
                        Annuler le ticket
                    </Button>
                    <Link 
                        :href="route('tickets.index')" 
                        class="bg-indigo-900/30 hover:bg-indigo-800/40 border border-indigo-500/30 hover:border-indigo-400/50 text-white hover:text-cyan-300 px-4 py-2 rounded-md"
                    >
                        Retour à la liste
                    </Link>
                </div>
            </div>
        </div>
    </AppLayout>
</template> 