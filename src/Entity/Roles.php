<?php

namespace App\Entity;

enum Roles: string
{
    case ROLE_ADMIN = 'ROLE_ADMINISTRATEUR';
    case ROLE_TRAINER = 'ROLE_ENTRAINEUR';
    case ROLE_USER = 'ROLE_UTILISATEUR';
}
