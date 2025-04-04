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
import UserInfo from "@/components/UserInfo.vue";

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
                class="cursor-pointer p-2 hover:bg-gray-300 dark:hover:bg-neutral-800 rounded"
            >
                <DropdownMenu class="w-max">
                    <!-- Trigger for the Dropdown Menu -->
                    <DropdownMenuTrigger class="flex items-center w-full">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                            <UserInfo :user="user" :show-email="true" />
                        </div>
                    </DropdownMenuTrigger>

                    <!-- Dropdown Menu Content -->
                    <DropdownMenuContent class=" dark:bg-neutral-900 dark:text-gray-200 shadow-lg border border-neutral-800">
                        <DropdownMenuItem @click="editUser(user)">
                            <span class="text-sm dark:text-gray-200">Modifier l'utilisateur</span>
                        </DropdownMenuItem>
                        <DropdownMenuItem @click="deleteUser(user)">
                            <span class="text-sm dark:text-gray-200">Supprimer l'utilisateur</span>
                        </DropdownMenuItem>
                        <DropdownMenuItem @click="assignAccessLevel(user)">
                            <span class="text-sm dark:text-gray-200">Attribuer un niveau d'accès</span>
                        </DropdownMenuItem>
                        <DropdownMenuItem @click="viewPoints(user)">
                            <span class="text-sm dark:text-gray-200">Superviser les points</span>
                        </DropdownMenuItem>
                        <DropdownMenuItem @click="viewLoginHistory(user)">
                            <span class="text-sm dark:text-gray-200">Consulter l'historique de connexion</span>
                        </DropdownMenuItem>
                    </DropdownMenuContent>
                </DropdownMenu>
            </li>
        </ul>
        <!-- Add User Button -->
        <Button
            class="w-full mt-2 bg-gray-800 text-gray-300 rounded hover:bg-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700"
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

/* Ultra Dark Dropdown Menu */
.dark .bg-gray-950 {
    background-color: #0a0a0a; /* Ultra dark background */
}

.dark .text-gray-200 {
    color: #e5e5e5; /* Light gray text for contrast */
}

.dark .hover\:text-white:hover {
    color: #ffffff; /* White text on hover */
}

.dark .border-gray-800 {
    border-color: #1a1a1a; /* Dark border for dropdown */
}
</style>

