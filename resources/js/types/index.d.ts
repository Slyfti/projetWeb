import type { PageProps as InertiaPageProps } from '@inertiajs/core';
import type { LucideIcon } from 'lucide-vue-next';
import type { Config } from 'ziggy-js';

export interface Auth {
    user: User;
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
    dateNaissance?: string;
    sexe?: string;
    niveau: string;
    points: number;
    typeMembre: string;
    photo?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
}

export interface SharedData extends InertiaPageProps {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    ziggy: Config & { location: string };
}

export interface ObjetConnecte {
    idObjetsConnectes: number;
    nom: string;
    idCategorie: number;
    descriptionObjetsConnectes: string;
    etat: 'Actif' | 'Inactif' | 'Maintenance';
    mode: 'Automatique' | 'Manuel';
    connectivite: string;
    niveauBatterie: number;
    derniereInteraction: string;
    puissance: number;
    consommationActuelle: number;
    dureeVieEstimee: number;
    dateInstallation: string;
    derniereMaintenance: string;
    idZone: number;
    categorie?: {
        nom: string;
    };
    zone?: {
        nom: string;
    };
}

export interface Categorie {
    idCategoriesObjets: number;
    nom: string;
}

export interface Zone {
    idZonesStade: number;
    nom: string;
}

export interface PageProps extends InertiaPageProps {
    users: User[];
    objets: ObjetConnecte[];
    categories: Categorie[];
    zones: Zone[];
}

export type BreadcrumbItemType = BreadcrumbItem;
