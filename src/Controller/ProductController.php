<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ImageRepository;
use App\Repository\ProductRepository;
use App\Service\FileUploadServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/product")
 */
class ProductController extends Controller
{
    /** @var FileUploadServiceInterface $uploadService */
    private $uploadService;

    public function __construct(FileUploadServiceInterface $uploadService)
    {
        $this->uploadService = $uploadService;
    }

    /**
     * @Route("/", name="product_index", methods={"GET"})
     */
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('product/index.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="product_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $files = $request->files->all()['product']['images'];
            $entityManager = $this->getDoctrine()->getManager();
            $images = $form->get('images')->getData();
            foreach ($images as $index => $image) {
                if (!isset($files[$index])) {
                    continue;
                }
                $uploadFile = $this->uploadService->upload($files[$index]['file'], $image->getTitle());
                $product->addImage($uploadFile);
            }
            $entityManager->persist($product);
            $entityManager->flush();
            $this->addFlash('notice', 'The Product was created successfully!');

            return $this->redirectToRoute('product_index');
        }

        return $this->render('product/new.html.twig', [
            'product' => $product,
            'form'    => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="product_show", methods={"GET"})
     */
    public function show(Product $product): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="product_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Product $product, ImageRepository $imageRepository): Response
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $files = $request->files->all()['product']['images'] ?? [];
            $toRemoveStr = trim($request->request->get('product')['removed'], ',');
            $toRemove = empty($toRemoveStr) ? [] : preg_split('#,#', $toRemoveStr);
            foreach ($toRemove as $idDel) {
                $image = $imageRepository->find($idDel);
                if (!isset($image)) {
                    continue;
                }
                $em->remove($image);
            }

            $images = $form->get('images')->getData();
            foreach ($images as $index => $image) {
                if (!isset($files[$index])) {
                    continue;
                }
                $uploadFile = $this->uploadService->upload($files[$index]['file'], $image->getTitle());
                $product->addImage($uploadFile);
            }

            $em->flush();
            $this->addFlash('notice', 'The Post was updated successfully!');

            return $this->redirectToRoute('product_index');
        }

        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'form'    => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="product_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Product $product): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($product);
            $entityManager->flush();
            $this->addFlash('notice', 'The Product was deleted successfully!');
        }

        return $this->redirectToRoute('product_index');
    }
}
