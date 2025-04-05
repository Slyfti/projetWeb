import type { PageProps as InertiaPageProps } from '@inertiajs/core';
import type { LucideIcon } from 'lucide-vue-next';
import type { Config } from 'ziggy-js';

export interface Auth {
    user: Utilisateur;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavItem {
    title: string;
    href: string;
    icon?: LucideIcon;
    isActive?: boolean;
}

export interface User {
    id: number;
    pseudo: string;
    email: string;
    nom?: string;
    prenom?: string;
    niveau: string;
    points: number;
    typeMembre: string;
}

export interface SharedData extends InertiaPageProps {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    ziggy: Config & { location: string };
}

export interface PageProps extends InertiaPageProps {
    users: User[];
}

export interface Utilisateur {
    id: number;
    pseudo: string;
    email: string;
    photo?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
}

export type BreadcrumbItemType = BreadcrumbItem;
