<template>
    <div class="ticket-list">
        <h2>Liste des tickets</h2>
        <div v-if="loading">Chargement...</div>
        <div v-else-if="error">{{ error }}</div>
        <div v-else>
            <table>
                <thead>
                    <tr>
                        <th>Numéro de ticket</th>
                        <th>Événement</th>
                        <th>Statut</th>
                        <th>Date d'achat</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="ticket in tickets" :key="ticket.idTicket">
                        <td>{{ ticket.numeroTicket }}</td>
                        <td>{{ ticket.evenement?.nom || 'N/A' }}</td>
                        <td>{{ getStatusLabel(ticket.statut) }}</td>
                        <td>{{ new Date(ticket.dateAchat).toLocaleDateString() }}</td>
                        <td>
                            <button @click="viewTicket(ticket)">Voir</button>
                            <button @click="cancelTicket(ticket)" v-if="ticket.statut === 'valide'">Annuler</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { Ticket } from '../../types';
import axios from 'axios';

const tickets = ref<Ticket[]>([]);
const loading = ref(true);
const error = ref<string | null>(null);

const fetchTickets = async () => {
    try {
        const response = await axios.get('/api/tickets');
        tickets.value = response.data;
    } catch (err) {
        error.value = 'Erreur lors du chargement des tickets';
    } finally {
        loading.value = false;
    }
};

const viewTicket = (ticket: Ticket) => {
    // Logique pour voir les détails du ticket
};

const cancelTicket = async (ticket: Ticket) => {
    try {
        await axios.post(`/api/tickets/${ticket.idTicket}/cancel`);
        await fetchTickets();
    } catch (err) {
        error.value = 'Erreur lors de l\'annulation du ticket';
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

onMounted(fetchTickets);
</script>

<style scoped>
.ticket-list {
    padding: 20px;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: left;
}

th {
    background-color: #f2f2f2;
}

button {
    margin-right: 5px;
}
</style> 