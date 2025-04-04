<script setup>
import { usePage } from "@inertiajs/vue3";
import Avatar from "@/components/ui/avatar/Avatar.vue";
import {
    DropdownMenu,
    DropdownMenuTrigger,
    DropdownMenuContent,
    DropdownMenuItem,
} from "@/components/ui/dropdown-menu";
import Button from "@/components/ui/button/Button.vue";

const { props } = usePage();
const users = props.users;

// Placeholder functions for actions
const addUser = () => {
    alert("Add User functionality triggered.");
};

const editUser = (user) => {
    alert(`Edit User: ${user.pseudo}`);
};

const deleteUser = (user) => {
    if (confirm(`Are you sure you want to delete ${user.pseudo}?`)) {
        alert(`User ${user.pseudo} deleted.`);
    }
};

const assignAccessLevel = (user) => {
    alert(`Assign access level to ${user.pseudo}`);
};

const viewPoints = (user) => {
    alert(`Viewing points for ${user.pseudo}`);
};

const viewLoginHistory = (user) => {
    alert(`Viewing login history for ${user.pseudo}`);
};
</script>

<template>
    <div class="user-list">
        <!-- User List -->
        <ul class="mt-6">
            <li
                v-for="user in users"
                :key="user.id"
                class="cursor-pointer p-2 hover:bg-gray-100 rounded"
            >
                <DropdownMenu class="w-max">
                    <!-- Trigger for the Dropdown Menu -->
                    <DropdownMenuTrigger class="flex items-center w-full">
                        <Avatar :src alt="User Avatar" class="mr-3" />
                        <span>{{ user.pseudo }} [id={{ user.id }}]</span>
                    </DropdownMenuTrigger>

                    <!-- Dropdown Menu Content -->
                    <DropdownMenuContent>
                        <DropdownMenuItem @click="editUser(user)">
                            <span class="text-sm text-gray-700">Modifier l'utilisateur</span>
                        </DropdownMenuItem>
                        <DropdownMenuItem @click="deleteUser(user)">
                            <span class="text-sm text-gray-700">Supprimer l'utilisateur</span>
                        </DropdownMenuItem>
                        <DropdownMenuItem @click="assignAccessLevel(user)">
                            <span class="text-sm text-gray-700">Attribuer un niveau d'accès</span>
                        </DropdownMenuItem>
                        <DropdownMenuItem @click="viewPoints(user)">
                            <span class="text-sm text-gray-700">Superviser les points</span>
                        </DropdownMenuItem>
                        <DropdownMenuItem @click="viewLoginHistory(user)">
                            <span class="text-sm text-gray-700">Consulter l'historique de connexion</span>
                        </DropdownMenuItem>
                    </DropdownMenuContent>
                </DropdownMenu>
            </li>
        </ul>
            <!-- Add User Button -->
        <Button
            class="w-full mt-2 bg-indigo-600 text-white rounded hover:bg-indigo-700"
            @click="addUser"
        >
            Ajouter un utilisateur
        </Button>

        
    </div>
</template>

<style scoped>
.user-list {
    max-width: 600px;
    margin: 0 auto;
}

button {
    cursor: pointer;
}
</style>

