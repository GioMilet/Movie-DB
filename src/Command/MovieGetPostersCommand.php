<?php

namespace App\Command;

use App\Repository\MovieRepository;
use App\Service\ImageUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class MovieGetPostersCommand extends Command
{
    protected static $defaultName = 'app:movie:get-posters';

    private $em;
    private $imageUploader;
    private $movieRepository;

    public function __construct(EntityManagerInterface $em, ImageUploader $imageUploader, MovieRepository $movieRepository)
    {
        parent::__construct();

        $this->em = $em;
        $this->imageUploader = $imageUploader;
        $this->movieRepository = $movieRepository;
    }

    protected function configure()
    {
        $this
            ->setDescription('Télécharge tous les posters de nos films depuis OMDbAPI')
          
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
      
        $movies = $this->movieRepository->findAll();

        foreach ($movies as $movie) {
            //change all the blank to '%20', avoid error 400
            $titleUrl = str_replace(' ', '%20', $movie->getTitle());
            //send request and store it into $jsonResponse
            $jsonResponse = file_get_contents('http://www.omdbapi.com/?apikey=45df58a5&t='.$titleUrl);
            // convert $jsonResponse to a json object
            $objectResponse = json_decode($jsonResponse);
            
            // here, we check if we got a 'True' in the json object and check if Poster is no equal to 'N/A' ('N/A' = no poster)
            if ($objectResponse->Response == 'True' && $objectResponse->Poster != 'N/A') {
                
                $image = file_get_contents($objectResponse->Poster);

                // use the method in imageUploader service to change the name 
                $filename = $this->imageUploader->getRandomFileName('jpg');
                
                // store the file in the folder
                file_put_contents('public/uploads/images/'.$filename, $image);

                //now we can add the new file name to the $movie
                $movie->setImageFilename($filename);
            }
        }

        $this->em->flush();
            
        $io = new SymfonyStyle($input, $output);
        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return 0;
    }
}
