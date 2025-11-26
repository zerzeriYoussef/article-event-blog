# Diagrammes UML - Fonctionnalités

Ce document contient les diagrammes PlantUML pour les fonctionnalités principales du système :
- **Notifications** (acceptation/rejet de participation)
- **Rejoindre un événement** (participation aux événements)
- **Paramètres SEO** (configuration et gestion)

## Diagramme de Classes

```plantuml
@startuml Diagramme de Classes - Notifications, Participation et SEO

package "Modèles" {
    class User {
        -id: int
        -name: string
        -email: string
        -avatar: string
        +eventParticipations()
        +participatingEvents()
        +notifications()
    }

    class Post {
        -id: int
        -name: array
        -slug: array
        -content: array
        -is_published: boolean
        -published_at: datetime
        -meta_description: array
        +author()
        +category()
        +participants()
        +pendingParticipants()
        +acceptedParticipants()
    }

    class EventParticipant {
        -id: int
        -post_id: int
        -user_id: int
        -status: string
        -message: text
        -responded_at: datetime
        +post()
        +user()
        +scopePending()
        +scopeAccepted()
        +scopeRejected()
    }

    class Notification {
        -id: string
        -type: string
        -notifiable_type: string
        -notifiable_id: int
        -data: json
        -read_at: datetime
        +notifiable()
    }
}

package "Notifications" {
    class EventParticipationAccepted {
        -post: Post
        +via(): array
        +toMail(): MailMessage
        +toArray(): array
    }

    class EventParticipationRejected {
        -post: Post
        +via(): array
        +toMail(): MailMessage
        +toArray(): array
    }
}

package "Paramètres SEO" {
    class SeoSettings {
        -meta_title: array
        -meta_description: array
        -meta_keywords: array
        -meta_author: array
        -og_title: array
        -og_description: array
        -tw_title: array
        -tw_description: array
        -meta_robots: string
        -meta_googlebot: string
        -meta_bingbot: string
        +getFormattedSettings(): array
    }
}

package "Contrôleurs" {
    class EventParticipantController {
        +store()
        +destroy()
        +accept()
        +reject()
    }

    class NotificationController {
        +index()
        +markAsRead()
        +markAllAsRead()
    }

    class MyEventsController {
        +index()
    }
}

package "Ressources Filament" {
    class EventParticipantResource {
        +form()
        +table()
        +actions()
    }

    class PostResource {
        +form()
        +table()
        +getRelations()
    }

    class ManageSeo {
        +form()
    }
}

' Relations
User ||--o{ EventParticipant : "fait des demandes"
Post ||--o{ EventParticipant : "reçoit des demandes"
User ||--o{ Notification : "reçoit"
User ||--o{ Post : "crée"
Post }o--|| User : "auteur"

EventParticipant ..> Notification : "génère"
EventParticipationAccepted ..> Post : "utilise"
EventParticipationRejected ..> Post : "utilise"

EventParticipantController ..> EventParticipant : "gère"
EventParticipantController ..> Notification : "envoie"
NotificationController ..> Notification : "gère"
MyEventsController ..> EventParticipant : "consulte"

@enduml
```

## Diagramme de Cas d'Utilisation

```plantuml
@startuml Diagramme de Cas d'Utilisation - Notifications, Participation et SEO

left to right direction

actor Utilisateur as User
actor Auteur as Author
actor Administrateur as Admin

rectangle "Système de Participation aux Événements" {
    usecase UC1 "Consulter la liste des événements" as UC1
    usecase UC2 "Rejoindre un événement" as UC2
    usecase UC3 "Annuler une demande de participation" as UC3
    usecase UC4 "Consulter mes événements" as UC4
    usecase UC5 "Voir le statut de ma demande" as UC5
}

rectangle "Système de Gestion des Participations" {
    usecase UC6 "Voir les demandes en attente" as UC6
    usecase UC7 "Accepter une demande" as UC7
    usecase UC8 "Rejeter une demande" as UC8
    usecase UC9 "Voir la liste des participants" as UC9
    usecase UC10 "Gérer les participants (admin)" as UC10
}

rectangle "Système de Notifications" {
    usecase UC11 "Recevoir une notification d'acceptation" as UC11
    usecase UC12 "Recevoir une notification de rejet" as UC12
    usecase UC13 "Consulter les notifications" as UC13
    usecase UC14 "Marquer une notification comme lue" as UC14
    usecase UC15 "Marquer toutes les notifications comme lues" as UC15
    usecase UC16 "Recevoir une notification par email" as UC16
}

rectangle "Système de Configuration SEO" {
    usecase UC17 "Configurer les paramètres SEO globaux" as UC17
    usecase UC18 "Définir le titre meta" as UC18
    usecase UC19 "Définir la description meta" as UC19
    usecase UC20 "Configurer les mots-clés" as UC20
    usecase UC21 "Configurer Open Graph" as UC21
    usecase UC22 "Configurer Twitter Cards" as UC22
    usecase UC23 "Configurer les robots meta" as UC23
    usecase UC24 "Optimiser le SEO d'un événement" as UC24
}

' Relations Utilisateur
User --> UC1
User --> UC2
User --> UC3
User --> UC4
User --> UC5
User --> UC13
User --> UC14
User --> UC15
User --> UC11
User --> UC12
User --> UC16

' Relations Auteur
Author --> UC1
Author --> UC6
Author --> UC7
Author --> UC8
Author --> UC9
Author --> UC24

' Relations Administrateur
Admin --> UC1
Admin --> UC6
Admin --> UC7
Admin --> UC8
Admin --> UC9
Admin --> UC10
Admin --> UC17
Admin --> UC18
Admin --> UC19
Admin --> UC20
Admin --> UC21
Admin --> UC22
Admin --> UC23
Admin --> UC24

' Relations entre cas d'utilisation
UC2 ..> UC11 : "déclenche"
UC7 ..> UC11 : "déclenche"
UC8 ..> UC12 : "déclenche"
UC11 ..> UC16 : "inclut"
UC12 ..> UC16 : "inclut"
UC24 ..> UC18 : "inclut"
UC24 ..> UC19 : "inclut"
UC24 ..> UC20 : "inclut"

@enduml
```

## Diagramme de Séquence - Rejoindre un Événement

```plantuml
@startuml Diagramme de Séquence - Rejoindre un Événement

actor Utilisateur as User
participant "Frontend" as Frontend
participant "EventParticipantController" as Controller
participant "EventParticipant" as Model
participant "Post" as Post
participant "Notification" as Notif
participant "Auteur" as Author

User -> Frontend: Clique sur "Rejoindre l'événement"
Frontend -> Controller: POST /posts/{slug}/join
Controller -> Model: Créer nouvelle participation
Model -> Model: status = 'pending'
Model -> Post: Vérifier l'événement
Post --> Model: Événement valide
Model --> Controller: Participation créée
Controller --> Frontend: Réponse succès
Frontend --> User: Confirmation affichée

note right of Model: La demande est en attente

Author -> Frontend: Consulte les demandes en attente
Frontend -> Controller: GET /admin/event-participants
Controller -> Model: Récupérer les demandes pending
Model --> Controller: Liste des demandes
Controller --> Frontend: Données
Frontend --> Author: Affichage des demandes

Author -> Frontend: Clique sur "Accepter"
Frontend -> Controller: POST /posts/{slug}/participants/{id}/accept
Controller -> Model: Mettre à jour le statut
Model -> Model: status = 'accepted'
Model -> Model: responded_at = now()
Model -> Notif: Créer notification d'acceptation
Notif -> User: Envoyer notification (DB + Email)
Notif --> Controller: Notification créée
Controller --> Frontend: Réponse succès
Frontend --> Author: Confirmation affichée

User -> Frontend: Consulte les notifications
Frontend -> Notif: GET /notifications
Notif --> Frontend: Liste des notifications
Frontend --> User: Affichage des notifications

@enduml
```

## Diagramme de Séquence - Rejeter une Participation

```plantuml
@startuml Diagramme de Séquence - Rejeter une Participation

actor Auteur as Author
participant "Frontend" as Frontend
participant "EventParticipantController" as Controller
participant "EventParticipant" as Model
participant "Notification" as Notif
participant "Utilisateur" as User

Author -> Frontend: Consulte les demandes en attente
Frontend -> Controller: GET /admin/event-participants
Controller -> Model: Récupérer les demandes pending
Model --> Controller: Liste des demandes
Controller --> Frontend: Données
Frontend --> Author: Affichage des demandes

Author -> Frontend: Clique sur "Rejeter"
Frontend -> Controller: POST /posts/{slug}/participants/{id}/reject
Controller -> Model: Mettre à jour le statut
Model -> Model: status = 'rejected'
Model -> Model: responded_at = now()
Model -> Notif: Créer notification de rejet
Notif -> User: Envoyer notification (DB + Email)
Notif --> Controller: Notification créée
Controller --> Frontend: Réponse succès
Frontend --> Author: Confirmation affichée

User -> Frontend: Consulte les notifications
Frontend -> Notif: GET /notifications
Notif --> Frontend: Liste des notifications
Frontend --> User: Affichage de la notification de rejet

@enduml
```

## Diagramme de Séquence - Configuration SEO

```plantuml
@startuml Diagramme de Séquence - Configuration SEO

actor Administrateur as Admin
participant "Filament Admin" as Filament
participant "ManageSeo" as SeoPage
participant "SeoSettings" as Settings
participant "Base de données" as DB

Admin -> Filament: Accède à la page SEO
Filament -> SeoPage: Affiche le formulaire
SeoPage -> Settings: Charge les paramètres actuels
Settings -> DB: Récupère les valeurs
DB --> Settings: Paramètres SEO
Settings --> SeoPage: Données
SeoPage --> Filament: Formulaire pré-rempli
Filament --> Admin: Formulaire affiché

Admin -> Filament: Modifie les paramètres SEO
Admin -> Filament: Remplit le formulaire
Admin -> Filament: Soumet le formulaire
Filament -> SeoPage: POST avec les données
SeoPage -> Settings: Valide les données
Settings -> Settings: Formate les données multilingues
Settings -> DB: Sauvegarde les paramètres
DB --> Settings: Confirmation
Settings --> SeoPage: Succès
SeoPage --> Filament: Message de succès
Filament --> Admin: Paramètres sauvegardés

note right of Settings: Les paramètres sont multilingues\n(EN, FR, ES)

Admin -> Filament: Configure le SEO d'un événement
Filament -> SeoPage: Affiche le formulaire d'événement
SeoPage -> Settings: Applique les paramètres globaux
Settings --> SeoPage: Paramètres par défaut
SeoPage --> Filament: Formulaire avec valeurs par défaut
Filament --> Admin: Formulaire pré-rempli

@enduml
```

## Diagramme d'État - Statut de Participation

```plantuml
@startuml Diagramme d'État - Statut de Participation

[*] --> EnAttente : Utilisateur fait une demande

EnAttente --> Accepté : Auteur accepte
EnAttente --> Rejeté : Auteur rejette
EnAttente --> Annulé : Utilisateur annule

Accepté --> [*] : Participation confirmée
Rejeté --> [*] : Participation refusée
Annulé --> [*] : Demande annulée

note right of EnAttente
  - Notification envoyée à l'auteur
  - Statut visible dans "Mes événements"
end note

note right of Accepté
  - Notification envoyée à l'utilisateur
  - Email de confirmation envoyé
  - Utilisateur apparaît dans la liste des participants
end note

note right of Rejeté
  - Notification envoyée à l'utilisateur
  - Email de rejet envoyé
  - L'utilisateur peut faire une nouvelle demande
end note

@enduml
```

## Diagramme de Composants - Architecture des Notifications

```plantuml
@startuml Diagramme de Composants - Architecture des Notifications

package "Frontend" {
    component "NotificationBell.vue" as Bell
    component "MyEvents.vue" as MyEvents
    component "BlogCard.vue" as BlogCard
}

package "Backend - Contrôleurs" {
    component "EventParticipantController" as EPController
    component "NotificationController" as NotifController
    component "MyEventsController" as MyEventsController
}

package "Backend - Modèles" {
    component "EventParticipant" as EPModel
    component "Post" as PostModel
    component "User" as UserModel
}

package "Notifications" {
    component "EventParticipationAccepted" as AcceptedNotif
    component "EventParticipationRejected" as RejectedNotif
}

package "Base de Données" {
    database "event_participants" as EPTable
    database "notifications" as NotifTable
    database "posts" as PostTable
    database "users" as UserTable
}

Bell --> NotifController : "GET /notifications"
Bell --> NotifController : "POST /notifications/{id}/read"
MyEvents --> MyEventsController : "GET /my-events"
BlogCard --> EPController : "POST /posts/{slug}/join"

EPController --> EPModel : "Créer/Mettre à jour"
EPController --> AcceptedNotif : "Envoyer notification"
EPController --> RejectedNotif : "Envoyer notification"
NotifController --> NotifTable : "Lire/Écrire"
MyEventsController --> EPModel : "Récupérer participations"

EPModel --> EPTable : "CRUD"
EPModel --> PostModel : "Relation"
EPModel --> UserModel : "Relation"
PostModel --> PostTable : "CRUD"
UserModel --> UserTable : "CRUD"

AcceptedNotif --> NotifTable : "Créer notification"
RejectedNotif --> NotifTable : "Créer notification"
AcceptedNotif --> UserModel : "Notifier utilisateur"
RejectedNotif --> UserModel : "Notifier utilisateur"

@enduml
```

## Légende et Notes

### Modèles de Données

- **User** : Représente un utilisateur du système
- **Post** : Représente un événement/publication
- **EventParticipant** : Représente une demande de participation à un événement
- **Notification** : Représente une notification système
- **SeoSettings** : Représente les paramètres SEO globaux

### Statuts de Participation

- **pending** : Demande en attente de réponse
- **accepted** : Demande acceptée par l'auteur
- **rejected** : Demande rejetée par l'auteur

### Canaux de Notification

- **database** : Notification stockée en base de données
- **mail** : Notification envoyée par email

### Paramètres SEO

Les paramètres SEO sont multilingues et supportent :
- **EN** (Anglais)
- **FR** (Français)
- **ES** (Espagnol)

Chaque paramètre peut avoir une valeur différente selon la langue.

---

*Généré avec PlantUML - Pour visualiser ces diagrammes, utilisez un plugin PlantUML dans votre IDE ou visitez [plantuml.com](http://www.plantuml.com/plantuml/uml/)*

