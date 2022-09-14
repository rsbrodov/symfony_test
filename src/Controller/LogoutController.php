<?
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class LogoutController extends AbstractController
{
    /**
     * @Route("/logout", name="app_logout", methods={"GET"})
     */
    public function logout(): void
    {
        // контроллер может быть пустым: он не будет вызван!
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }
}