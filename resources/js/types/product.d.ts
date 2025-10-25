interface Category {
    id: number;
    name: string;
    slug: string;
    created_at: string;
    updated_at: string;
}

interface ProductImage {
    id: number;
    product_id: number;
    path: string; // The public URL to the image
    sort_order: number;
    created_at: string;
    updated_at: string;
}

interface Product {
    id: number;
    category_id: number;
    name: string;
    description: string | null;
    sku: string;
    price: number;
    stock_quantity: number;
    is_active: boolean;
    created_at: string;
    updated_at: string;
    // Relationships (eager loaded)
    category: Category;
    images: ProductImage[];
}

// Inertia Props Interface (assuming default Laravel Breeze/Jetstream Auth)
interface InertiaProps extends PageProps {
    auth: {
        user: App.Models.User;
    };
    // Add custom flash messages if you use them
    flash: {
        message: string | null;
        success: string | null;
        error: string | null;
    };
}

// For use in the Index component
interface ProductsIndexProps extends InertiaProps {
    products: Product[]
}

// For use in the Form components
interface ProductFormProps extends InertiaProps {
    categories: Category[];
    product?: Product; // Optional for Create view
    breadcrumbs: BreadcrumbItem[];
}


export {Category, ProductImage, Product, ProductFormProps, ProductsIndexProps, InertiaProps}