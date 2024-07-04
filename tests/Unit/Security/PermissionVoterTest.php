<?php

namespace App\Tests\Unit\Security;

use App\Security\PermissionVoter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

class PermissionVoterTest extends TestCase
{
    public function testVoteAccessGranted()
    {
        // Créez l'instance de votre Voter
        $voter = new PermissionVoter();

        // Créez un mock pour TokenInterface (non utilisé dans cet exemple)
        $tokenMock = $this->createMock(TokenInterface::class);

        // Sujet factice pour le test
        $subject = new \stdClass();

        // Liste des attributs à tester
        $attributes = ['EDIT'];

        // Exemple de test où l'accès est accordé
        $result = $voter->vote($tokenMock, $subject, $attributes);

        // Vérifiez si l'accès est accordé (ACCESS_GRANTED)
        $this->assertEquals(VoterInterface::ACCESS_GRANTED, $result);
    }

    public function testVoteAccessDenied()
    {
        // Créez l'instance de votre Voter
        $voter = new PermissionVoter();

        // Créez un mock pour TokenInterface (non utilisé dans cet exemple)
        $tokenMock = $this->createMock(TokenInterface::class);

        // Sujet factice pour le test
        $subject = new \stdClass();

        // Liste des attributs à tester (dans cet exemple, l'accès est refusé)
        $attributes = ['DELETE'];

        // Exemple de test où l'accès est refusé
        $result = $voter->vote($tokenMock, $subject, $attributes);

        // Vérifiez si l'accès est refusé (ACCESS_DENIED)
        $this->assertEquals(VoterInterface::ACCESS_DENIED, $result);
    }
}
