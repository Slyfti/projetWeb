export interface Evenement {
    idEvenements: number;
    nom: string;
    dateEvenements: string;
    lieu: string;
    typeEvents: string;
    equipeDomicile: string;
    equipeExterieur: string;
}

export interface Utilisateur {
    idUtilisateur: number;
    pseudo: string;
    nom: string;
    prenom: string;
}

export interface Ticket {
    idTicket: number;
    idUtilisateur: number;
    idEvenements: number;
    numeroTicket: string;
    statut: 'valide' | 'annule' | 'utilise';
    dateAchat: string;
    dateUtilisation: string | null;
    typePlace: string;
    numeroPlace: string;
    prixPaye: number;
    notes: string | null;
    created_at: string;
    updated_at: string;
    evenement?: Evenement;
    utilisateur?: Utilisateur;
}

export interface NavItem {
    title: string;
    href: string;
    icon?: any;
    showForTypes?: string[];
}

export interface SharedData {
    auth: {
        user: {
            id: number;
            typeMembre: string;
            nom: string;
            prenom: string;
            email: string;
        };
    };
}

export interface PageProps {
    tickets: Ticket[];
    isAdmin: boolean;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
} 