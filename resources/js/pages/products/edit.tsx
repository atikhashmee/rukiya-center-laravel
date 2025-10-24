import ProductForm from './form';
import { ProductFormProps } from '@/types/product';
import { BreadcrumbItem } from "@/types";
import { create, index } from '@/routes/products';   
import { dashboard } from '@/routes';


const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
    {
        title: 'Products',
        href: index().url,
    },
    {
        title: 'Create',
        href: create().url,
    },
];

export default function Edit(props: ProductFormProps) {
    return <ProductForm {...props}  breadcrumbs={breadcrumbs}/>;
}