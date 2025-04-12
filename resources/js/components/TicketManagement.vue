<script setup lang="ts">
import { ref, computed } from 'vue';
import { useForm, router, usePage } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { type Ticket } from '@/types';
import {
    Dialog,
    DialogContent,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import {
    Form,
    FormControl,
    FormField,
    FormItem,
    FormLabel,
    FormMessage,
} from '@/components/ui/form';
import { Input } from '@/components/ui/input';
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import InputError from '@/components/InputError.vue';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Badge } from '@/components/ui/badge';
import { useToast } from '@/components/ui/toast/use-toast';
import type { PageProps } from '@/types';

interface TicketWithRelations extends Ticket {
    evenement: {
        idEvenements: number;
        nom: string;
        dateEvenements: string;
        lieu: string;
        typeEvents: string;
        equipeDomicile: string;
        equipeExterieur: string;
    };
    utilisateur?: {
        idUtilisateur: number;
        pseudo: string;
        nom: string;
        prenom: string;
    };
}

const props = defineProps<{
    tickets: TicketWithRelations[];
    isAdmin?: boolean;
}>();

const { toast } = useToast();
const search = ref('');
const isDetailsDialogOpen = ref(false);
const selectedTicket = ref<TicketWithRelations | null>(null);

const page = usePage<PageProps>();
const isAdmin = computed(() => {
    if (props.isAdmin !== undefined) return props.isAdmin;
    const auth = page.props.auth as { user: any };
    return auth.user.typeMembre === 'Administratif';
});

const form = useForm({
    statut: '',
    notes: ''
});

const filteredTickets = computed(() => {
    if (!search.value) return props.tickets;
    const searchLower = search.value.toLowerCase();
    return props.tickets.filter(ticket => 
        ticket.numeroTicket.toLowerCase().includes(searchLower) ||
        ticket.evenement.nom.toLowerCase().includes(searchLower) ||
        (ticket.utilisateur && ticket.utilisateur.pseudo.toLowerCase().includes(searchLower))
    );
});

const getStatusBadgeClass = (status: string) => {
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

const getStatusLabel = (status: string) => {
    switch (status) {
        case 'valide':
            return 'Valide';
        case 'utilise':
            return 'Utilisé';
        case 'annule':
            return 'Annulé';
        default:
            return status;
    }
};

const viewTicketDetails = (ticket: TicketWithRelations) => {
    selectedTicket.value = ticket;
    isDetailsDialogOpen.value = true;
};

const updateTicket = () => {
    if (!selectedTicket.value) return;

    form.put(`/tickets/${selectedTicket.value.idTicket}`, {
        onSuccess: () => {
            isDetailsDialogOpen.value = false;
            form.reset();
            router.visit('/dashboard/tickets', { preserveScroll: true });
            toast({
                title: "Succès",
                description: "Ticket mis à jour avec succès",
                variant: "default"
            });
        },
        onError: () => {
            toast({
                title: "Erreur",
                description: "Impossible de mettre à jour le ticket",
                variant: "destructive"
            });
        }
    });
};

const cancelTicket = (ticket: TicketWithRelations) => {
    if (!confirm('Êtes-vous sûr de vouloir annuler ce ticket ?')) return;

    router.post(`/tickets/${ticket.idTicket}/cancel`, {}, {
        onSuccess: () => {
            // Mettre à jour le statut du ticket localement
            const index = props.tickets.findIndex(t => t.idTicket === ticket.idTicket);
            if (index !== -1) {
                props.tickets[index].statut = 'annule';
            }
            
            // Afficher un message de succès
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

const deleteTicket = (ticket: TicketWithRelations) => {
    if (!confirm('Êtes-vous sûr de vouloir supprimer ce ticket ? Cette action est irréversible.')) return;

    // Afficher un message de chargement
    toast({
        title: "Traitement en cours",
        description: "Suppression du ticket en cours...",
        variant: "default"
    });

    router.delete(`/tickets/${ticket.idTicket}`, {}, {
        onSuccess: () => {
            // Afficher un message de succès
            toast({
                title: "Succès",
                description: "Ticket supprimé avec succès",
                variant: "default"
            });
            
            // Recharger les données sans actualiser la page
            router.reload({ only: ['tickets'] });
        },
        onError: () => {
            toast({
                title: "Erreur",
                description: "Impossible de supprimer le ticket",
                variant: "destructive"
            });
        }
    });
};

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

const handleSearch = () => {
    router.get(route('tickets.index'), { search: search.value }, {
        preserveState: true,
        preserveScroll: true
    });
};
</script>

<template>
    <Card>
        <CardHeader>
            <h2 class="text-2xl font-bold">Gestion des tickets</h2>
        </CardHeader>
        <div class="p-4">
            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead>Numéro de ticket</TableHead>
                        <TableHead>Événement</TableHead>
                        <TableHead>Type de place</TableHead>
                        <TableHead>Statut</TableHead>
                        <TableHead>Date d'achat</TableHead>
                        <TableHead>Prix</TableHead>
                        <TableHead>Actions</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="ticket in filteredTickets" :key="ticket.idTicket">
                        <TableCell>{{ ticket.numeroTicket }}</TableCell>
                        <TableCell>{{ ticket.evenement.nom }}</TableCell>
                        <TableCell>{{ ticket.typePlace }}</TableCell>
                        <TableCell>{{ getStatusLabel(ticket.statut) }}</TableCell>
                        <TableCell>{{ formatDate(ticket.dateAchat) }}</TableCell>
                        <TableCell>{{ ticket.prixPaye }}€</TableCell>
                        <TableCell>
                            <div class="flex gap-2">
                                <Button variant="outline" @click="viewTicketDetails(ticket)">
                                    Détails
                                </Button>
                                <Button 
                                    variant="destructive" 
                                    @click="deleteTicket(ticket)"
                                >
                                    Supprimer
                                </Button>
                            </div>
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>
    </Card>

    <!-- Dialog pour les détails du ticket -->
    <Dialog v-model:open="isDetailsDialogOpen">
        <DialogContent class="bg-gray-900 border border-indigo-500/30 text-white">
            <DialogHeader>
                <DialogTitle>Détails du ticket</DialogTitle>
            </DialogHeader>
            
            <div v-if="selectedTicket" class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <h4 class="text-sm font-medium text-white/70">Numéro de ticket</h4>
                        <p class="text-white">{{ selectedTicket.numeroTicket }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-white/70">Statut</h4>
                        <Badge :class="getStatusBadgeClass(selectedTicket.statut)">
                            {{ getStatusLabel(selectedTicket.statut) }}
                        </Badge>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-white/70">Événement</h4>
                        <p class="text-white">{{ selectedTicket.evenement.nom }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-white/70">Date de l'événement</h4>
                        <p class="text-white">{{ formatDate(selectedTicket.evenement.dateEvenements) }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-white/70">Lieu</h4>
                        <p class="text-white">{{ selectedTicket.evenement.lieu }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-white/70">Type de place</h4>
                        <p class="text-white">{{ selectedTicket.typePlace }} {{ selectedTicket.numeroPlace ? `- ${selectedTicket.numeroPlace}` : '' }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-white/70">Prix payé</h4>
                        <p class="text-white">{{ selectedTicket.prixPaye }}€</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-white/70">Date d'achat</h4>
                        <p class="text-white">{{ formatDate(selectedTicket.dateAchat) }}</p>
                    </div>
                    <div v-if="selectedTicket.dateUtilisation">
                        <h4 class="text-sm font-medium text-white/70">Date d'utilisation</h4>
                        <p class="text-white">{{ formatDate(selectedTicket.dateUtilisation) }}</p>
                    </div>
                    <div v-if="isAdmin && selectedTicket.utilisateur">
                        <h4 class="text-sm font-medium text-white/70">Utilisateur</h4>
                        <p class="text-white">{{ selectedTicket.utilisateur.pseudo }} ({{ selectedTicket.utilisateur.nom }} {{ selectedTicket.utilisateur.prenom }})</p>
                    </div>
                </div>
                
                <div v-if="selectedTicket.notes">
                    <h4 class="text-sm font-medium text-white/70">Notes</h4>
                    <p class="text-white">{{ selectedTicket.notes }}</p>
                </div>
                
                <div v-if="selectedTicket.statut === 'valide'">
                    <Form @submit="updateTicket" class="space-y-4">
                        <FormField name="statut">
                            <FormItem>
                                <FormLabel>Statut</FormLabel>
                                <Select v-model="form.statut">
                                    <SelectTrigger class="bg-indigo-900/30 border-indigo-500/30 text-white">
                                        <SelectValue placeholder="Sélectionner un statut" />
                                    </SelectTrigger>
                                    <SelectContent class="bg-gray-900 border-indigo-500/30 text-white">
                                        <SelectGroup>
                                            <SelectItem value="valide">Valide</SelectItem>
                                            <SelectItem value="utilise">Utilisé</SelectItem>
                                            <SelectItem value="annule">Annulé</SelectItem>
                                        </SelectGroup>
                                    </SelectContent>
                                </Select>
                                <FormMessage />
                            </FormItem>
                        </FormField>
                        
                        <FormField name="notes">
                            <FormItem>
                                <FormLabel>Notes</FormLabel>
                                <FormControl>
                                    <Input v-model="form.notes" class="bg-indigo-900/30 border-indigo-500/30 text-white" />
                                </FormControl>
                                <FormMessage />
                            </FormItem>
                        </FormField>
                        
                        <div class="flex justify-end gap-2">
                            <Button type="button" variant="outline" @click="isDetailsDialogOpen = false">
                                Annuler
                            </Button>
                            <Button type="submit" class="bg-indigo-900/30 hover:bg-indigo-800/40 border border-indigo-500/30 hover:border-indigo-400/50 text-white hover:text-cyan-300">
                                Mettre à jour
                            </Button>
                        </div>
                    </Form>
                </div>
            </div>
        </DialogContent>
    </Dialog>
</template> 