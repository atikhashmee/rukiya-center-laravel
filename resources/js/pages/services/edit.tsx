import React, { useEffect } from 'react';
// --- START: TEMPORARY FIX FOR COMPILATION ERRORS ---
// NOTE: Please revert these lines to your original imports in your local environment
import AppLayout from "@/layouts/app-layout";
import { Head, router } from '@inertiajs/react';
const Link: React.FC<any> = ({ children, href, className, ...props }) => <a href={href} className={className} {...props}>{children}</a>;
const useForm = (initialData: any) => {
    const [data, setData] = React.useState(initialData);
    const [errors, setErrors] = React.useState<Record<string, string>>({});
    const processing = false;
    const post = (url: string, options: any) => { 
        console.log(`Mock POST to ${url} (CREATE)`);
    };
    const put = (url: string, options: any) => { 
        console.log(`Mock PUT to ${url} (EDIT) with data:`, data); 
        console.log("Success! Submitting update...");
        options.onSuccess(); 
    };
    return { data, setData: (keyOrObject: string | Record<string, any>, value?: any) => { if (typeof keyOrObject === 'string') { setData(prev => ({ ...prev, [keyOrObject]: value })); } else { setData(prev => ({ ...prev, ...keyOrObject })); } }, errors, processing, post, put, reset: () => setData(initialData), wasSuccessful: false, };
};
// --- END: TEMPORARY FIX ---

// UI Components (Using mocked components for non-standard UI elements)
const Button: React.FC<any> = ({ children, className = '', ...props }) => <button {...props} className={`px-4 py-2 rounded-lg font-medium transition-colors ${className}`}>{children}</button>;
const Input: React.FC<any> = (props) => <input {...props} className="border border-gray-300 p-2 rounded-lg w-full focus:ring-indigo-500 focus:border-indigo-500" />;
const Label: React.FC<any> = ({ children, ...props }) => <label {...props} className="block text-sm font-medium text-gray-700 mb-1">{children}</label>;
const Textarea: React.FC<any> = (props) => <textarea {...props} className="border border-gray-300 p-2 rounded-lg w-full focus:ring-indigo-500 focus:border-indigo-500"></textarea>;
const Select: React.FC<any> = ({ children, onValueChange, value, ...props }) => (
    <div className="relative">
        <select onChange={(e) => onValueChange(e.target.value)} value={value} className="appearance-none border border-gray-300 p-2 pr-8 rounded-lg w-full bg-white focus:ring-indigo-500 focus:border-indigo-500">
            {children}
        </select>
        <div className="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
            <svg className="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
        </div>
    </div>
);
const SelectContent: React.FC<any> = ({ children }) => <>{children}</>;
const SelectItem: React.FC<any> = ({ value, children }) => <option value={value}>{children}</option>;
const SelectTrigger: React.FC<any> = ({ children }) => <>{children}</>;
const SelectValue: React.FC<any> = ({ placeholder }) => <option disabled value="">{placeholder}</option>;
const Checkbox: React.FC<any> = (props) => <input type="checkbox" {...props} className="rounded text-indigo-600 h-4 w-4 border-gray-300 focus:ring-indigo-500" checked={props.checked} onChange={(e) => props.onCheckedChange(e.target.checked)} />;

import { CornerUpLeft, Save, AlertTriangle, Edit3 } from 'lucide-react';

// --- Type Definitions ---
type PriceType = 'FREE' | 'DONATION' | 'FIXED' | 'RESERVATION';

interface ServiceOptionData {
    id: number; // Only present on edit/existing data
    id_code: string;
    category: string;
    title: string;
    tagline: string;
    description: string;
    icon: string;
    card_color: string;
    features: string | string[]; 
    order: number;
    price_type: PriceType;
    price_value: number | null;
    min_donation: number | null;
    requires_custom_assessment: boolean;
    required_form_fields: string | string[]; 
    submit_button_text: string;
}

interface ServiceOptionFormData extends Omit<ServiceOptionData, 'id' | 'features' | 'required_form_fields'> {
    features: string;
    required_form_fields: string;
}

interface BreadcrumbItem {
    title: string;
    href: string;
}

interface EditServiceOptionProps {
    serviceOption: ServiceOptionData;
    serviceId: number;
    serviceName: string;
}

// --- Mock Route Helpers ---
const mockRoutes = {
    services: {
        index: () => ({ url: '/admin/services' }),
        optionsIndex: (serviceId: number) => ({ url: `/admin/services/${serviceId}/options` }),
        updateOption: (serviceId: number, optionId: number) => `/admin/services/${serviceId}/options/${optionId}`,
    },
    dashboard: () => ({ url: '/admin/dashboard' }),
};

// --- Mock Existing Data (For Canvas Preview) ---
const MOCK_SERVICE_ID = 42;
const MOCK_SERVICE_NAME = "Deep Istekhara & Guidance";
const MOCK_SERVICE_OPTION: ServiceOptionData = {
    id: 101,
    id_code: 'DEEP_DIVE',
    category: 'istekhara',
    title: 'Advanced Istekhara Report',
    tagline: 'In-depth analysis for complex matters.',
    description: 'This service provides a comprehensive report based on advanced Istekhara methods, suitable for life-altering decisions.',
    icon: 'BookOpen',
    card_color: 'border-l-teal-500',
    features: ['Detailed response', '3-day delivery', 'Follow-up consultation'],
    order: 2,
    price_type: 'FIXED',
    price_value: 120.00,
    min_donation: null,
    requires_custom_assessment: false,
    required_form_fields: ['fullName', 'dateOfBirth', 'detailedQuestion'],
    submit_button_text: 'Purchase Report',
};


// --- Main Page Component (Contains the full form logic for EDIT) ---

export default function Edit({ 
    serviceOption = MOCK_SERVICE_OPTION, 
    serviceId = MOCK_SERVICE_ID, 
    serviceName = MOCK_SERVICE_NAME 
}: EditServiceOptionProps) {
    
    const pageTitle = `Editing Option: ${serviceOption.title}`;

    // Helper to prepare the existing data for the form's state, converting array/object props to JSON strings
    const prepareInitialData = (option: ServiceOptionData): ServiceOptionFormData => ({
        ...option,
        // Ensure features and required_form_fields are JSON strings for the Textarea inputs
        features: Array.isArray(option.features) ? JSON.stringify(option.features) : option.features,
        required_form_fields: Array.isArray(option.required_form_fields) ? JSON.stringify(option.required_form_fields) : option.required_form_fields,
    });

    // Initialize form with existing data
    const { data, setData, errors, processing, put } = useForm<ServiceOptionFormData>(
        prepareInitialData(serviceOption)
    );

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();

        // Prepare data for submission (parse JSON fields and handle nulls)
        const submitData = {
            ...data,
            features: JSON.parse(data.features || '[]'),
            required_form_fields: JSON.parse(data.required_form_fields || '[]'),
            price_value: data.price_type === 'FIXED' ? data.price_value : null,
            min_donation: data.price_type === 'DONATION' ? data.min_donation : null,
        };
        
        console.log("Final data structure being sent for Update:", submitData);

        // *** Use PUT method for update ***
        put(mockRoutes.services.updateOption(serviceId, serviceOption.id), {
            data: submitData,
            onSuccess: () => router.get(mockRoutes.services.optionsIndex(serviceId).url),
        });
    };

    const breadcrumbs: BreadcrumbItem[] = [
        { title: 'Dashboard', href: mockRoutes.dashboard().url },
        { title: 'Services', href: mockRoutes.services.index().url },
        { title: serviceName, href: mockRoutes.services.optionsIndex(serviceId).url },
        { title: 'Edit Option', href: '' },
    ];

    // Cleanup Effect for Price Fields on Type Change
    useEffect(() => {
        if (data.price_type === 'FREE' || data.price_type === 'RESERVATION') {
            setData({ price_value: null, min_donation: null });
        }
    }, [data.price_type, setData]);


    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={pageTitle} />
            <div className="container py-4 pl-4 max-w-4xl mx-auto">
                <div className="flex flex-col gap-6 w-full ">
                    
                    {/* Header and Back Button */}
                    <div className="flex justify-between items-center mb-4">
                        <h2 className="text-2xl font-bold text-gray-800 flex items-center">
                            <Edit3 className="mr-3 h-6 w-6 text-indigo-600" />
                            {pageTitle}
                        </h2>
                         <Link 
                            href={mockRoutes.services.optionsIndex(serviceId).url}
                            className="text-gray-600 border border-gray-300 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-100 transition-colors inline-flex items-center shadow-sm"
                        >
                            <CornerUpLeft className="mr-2 h-4 w-4" />
                            Back to Options
                        </Link>
                    </div>

                    {/* FULL FORM CARD */}
                    <form onSubmit={handleSubmit} className="p-6 border rounded-xl bg-white shadow-2xl space-y-8">
                        
                        {/* 1. Core Identification and Ordering */}
                        <div className="grid grid-cols-1 md:grid-cols-3 gap-6 border-b pb-6">
                            <div>
                                <h3 className="text-lg font-semibold text-indigo-700 mb-1">Core Identity</h3>
                                <p className="text-sm text-gray-500">Unique code, category, and display order.</p>
                            </div>
                            <div className="md:col-span-2 space-y-4">
                                
                                <div className='flex space-x-4'>
                                    {/* ID Code */}
                                    <div className='flex-1'>
                                        <Label htmlFor="id_code">ID Code (Unique)</Label>
                                        <Input
                                            id="id_code"
                                            value={data.id_code}
                                            onChange={(e: any) => setData('id_code', e.target.value.toUpperCase().replace(/\s/g, '_'))}
                                            className={errors.id_code ? 'border-red-500' : ''}
                                            placeholder="E.g., ISTEKHARA_DEEP"
                                            // Disabled on Edit to prevent key changes, typically done in real applications
                                            disabled
                                        />
                                        {errors.id_code && <p className="text-xs text-red-500 mt-1">{errors.id_code}</p>}
                                    </div>

                                    {/* Order */}
                                    <div className='w-20'>
                                        <Label htmlFor="order">Order</Label>
                                        <Input
                                            id="order"
                                            type="number"
                                            value={data.order}
                                            onChange={(e: any) => setData('order', parseInt(e.target.value) || 1)}
                                            className={errors.order ? 'border-red-500' : ''}
                                        />
                                        {errors.order && <p className="text-xs text-red-500 mt-1">{errors.order}</p>}
                                    </div>
                                </div>

                                {/* Category */}
                                <div>
                                    <Label htmlFor="category">Category (Parent Service)</Label>
                                    <Select 
                                        value={data.category} 
                                        onValueChange={(value: string) => setData('category', value)}
                                    >
                                        <SelectTrigger className={errors.category ? 'border-red-500' : ''}>
                                            <SelectValue placeholder="Select Category" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem value="counseling">Counseling</SelectItem>
                                            <SelectItem value="rukiya">Rukiya</SelectItem>
                                            <SelectItem value="istekhara">Istekhara</SelectItem>
                                            <SelectItem value="other">Other</SelectItem>
                                        </SelectContent>
                                    </Select>
                                    {errors.category && <p className="text-xs text-red-500 mt-1">{errors.category}</p>}
                                </div>
                            </div>
                        </div>

                        {/* 2. Content & Presentation */}
                        <div className="grid grid-cols-1 md:grid-cols-3 gap-6 border-b pb-6">
                            <div>
                                <h3 className="text-lg font-semibold text-indigo-700 mb-1">Content & Design</h3>
                                <p className="text-sm text-gray-500">Titles, descriptions, icons, and visual styling.</p>
                            </div>
                            <div className="md:col-span-2 space-y-4">
                                {/* Title */}
                                <div>
                                    <Label htmlFor="title">Title</Label>
                                    <Input
                                        id="title"
                                        value={data.title}
                                        onChange={(e: any) => setData('title', e.target.value)}
                                        className={errors.title ? 'border-red-500' : ''}
                                        placeholder="E.g., Full Istekhara Assessment"
                                    />
                                    {errors.title && <p className="text-xs text-red-500 mt-1">{errors.title}</p>}
                                </div>

                                {/* Tagline */}
                                <div>
                                    <Label htmlFor="tagline">Tagline</Label>
                                    <Input
                                        id="tagline"
                                        value={data.tagline}
                                        onChange={(e: any) => setData('tagline', e.target.value)}
                                        className={errors.tagline ? 'border-red-500' : ''}
                                        placeholder="Brief, punchy description."
                                    />
                                    {errors.tagline && <p className="text-xs text-red-500 mt-1">{errors.tagline}</p>}
                                </div>

                                {/* Description */}
                                <div>
                                    <Label htmlFor="description">Full Description</Label>
                                    <Textarea
                                        id="description"
                                        value={data.description}
                                        onChange={(e: any) => setData('description', e.target.value)}
                                        className={errors.description ? 'border-red-500' : ''}
                                        placeholder="Detailed explanation of the service option."
                                    />
                                    {errors.description && <p className="text-xs text-red-500 mt-1">{errors.description}</p>}
                                </div>

                                {/* Icon and Color */}
                                <div className='grid grid-cols-2 gap-4'>
                                    <div>
                                        <Label htmlFor="icon">Lucide Icon Name</Label>
                                        <Input
                                            id="icon"
                                            value={data.icon}
                                            onChange={(e: any) => setData('icon', e.target.value)}
                                            className={errors.icon ? 'border-red-500' : ''}
                                            placeholder="E.g., Zap, Heart, Sunrise"
                                        />
                                        {errors.icon && <p className="text-xs text-red-500 mt-1">{errors.icon}</p>}
                                    </div>
                                    <div>
                                        <Label htmlFor="card_color">Card Highlight Color (Tailwind Class)</Label>
                                        <Input
                                            id="card_color"
                                            value={data.card_color}
                                            onChange={(e: any) => setData('card_color', e.target.value)}
                                            className={errors.card_color ? 'border-red-500' : ''}
                                            placeholder="E.g., border-l-red-500"
                                        />
                                        {errors.card_color && <p className="text-xs text-red-500 mt-1">{errors.card_color}</p>}
                                    </div>
                                </div>

                                {/* Features (JSON Textarea) */}
                                <div>
                                    <Label htmlFor="features">Features (JSON Array of Strings)</Label>
                                    <Textarea
                                        id="features"
                                        value={data.features}
                                        onChange={(e: any) => setData('features', e.target.value)}
                                        className={errors.features ? 'border-red-500 font-mono' : 'font-mono'}
                                        placeholder='["Feature 1", "Feature 2"]'
                                        rows={3}
                                    />
                                    {errors.features && <p className="text-xs text-red-500 mt-1">{errors.features}</p>}
                                </div>
                            </div>
                        </div>

                        {/* 3. Booking and Payment Logic */}
                        <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <h3 className="text-lg font-semibold text-indigo-700 mb-1">Pricing & Logic</h3>
                                <p className="text-sm text-gray-500">Payment type, values, and assessment requirement.</p>
                            </div>
                            <div className="md:col-span-2 space-y-4">
                                {/* Price Type */}
                                <div>
                                    <Label htmlFor="price_type">Price Type</Label>
                                    <Select 
                                        value={data.price_type} 
                                        onValueChange={(value: PriceType) => setData('price_type', value)}
                                    >
                                        <SelectTrigger className={errors.price_type ? 'border-red-500' : ''}>
                                            <SelectValue placeholder="Select Price Type" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem value="FIXED">FIXED (Set Price)</SelectItem>
                                            <SelectItem value="DONATION">DONATION (Minimum Contribution)</SelectItem>
                                            <SelectItem value="FREE">FREE</SelectItem>
                                            <SelectItem value="RESERVATION">RESERVATION (Assessment Required)</SelectItem>
                                        </SelectContent>
                                    </Select>
                                    {errors.price_type && <p className="text-xs text-red-500 mt-1">{errors.price_type}</p>}
                                </div>

                                {/* Dynamic Price Inputs */}
                                <div className='grid grid-cols-2 gap-4 transition-all duration-300'>
                                    {data.price_type === 'FIXED' && (
                                        <div>
                                            <Label htmlFor="price_value">Fixed Price (£)</Label>
                                            <Input
                                                id="price_value"
                                                type="number"
                                                step="0.01"
                                                value={data.price_value || ''}
                                                onChange={(e: any) => setData('price_value', parseFloat(e.target.value))}
                                                className={errors.price_value ? 'border-red-500' : ''}
                                                placeholder="e.g., 120.00"
                                            />
                                            {errors.price_value && <p className="text-xs text-red-500 mt-1">{errors.price_value}</p>}
                                        </div>
                                    )}

                                    {data.price_type === 'DONATION' && (
                                        <div>
                                            <Label htmlFor="min_donation">Minimum Donation (£)</Label>
                                            <Input
                                                id="min_donation"
                                                type="number"
                                                step="0.01"
                                                value={data.min_donation || ''}
                                                onChange={(e: any) => setData('min_donation', parseFloat(e.target.value))}
                                                className={errors.min_donation ? 'border-red-500' : ''}
                                                placeholder="e.g., 10.00"
                                            />
                                            {errors.min_donation && <p className="text-xs text-red-500 mt-1">{errors.min_donation}</p>}
                                        </div>
                                    )}
                                </div>


                                {/* Custom Assessment */}
                                <div className="flex items-center space-x-2 pt-2">
                                    <Checkbox
                                        id="requires_custom_assessment"
                                        checked={data.requires_custom_assessment}
                                        onCheckedChange={(checked: boolean) => setData('requires_custom_assessment', !!checked)}
                                    />
                                    <label
                                        htmlFor="requires_custom_assessment"
                                        className="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                                    >
                                        Requires Custom Assessment (Reservation-style booking)
                                    </label>
                                </div>
                                
                                {/* Required Form Fields (JSON Textarea) */}
                                <div>
                                    <Label htmlFor="required_form_fields">Required Form Fields (JSON Array of Strings)</Label>
                                    <Textarea
                                        id="required_form_fields"
                                        value={data.required_form_fields}
                                        onChange={(e: any) => setData('required_form_fields', e.target.value)}
                                        className={errors.required_form_fields ? 'border-red-500 font-mono' : 'font-mono'}
                                        placeholder='["motherName", "age", "symptoms"]'
                                        rows={3}
                                    />
                                    <p className="text-xs text-gray-500 mt-1">These fields are collected during booking (e.g., motherName, phone).</p>
                                    {errors.required_form_fields && <p className="text-xs text-red-500 mt-1">{errors.required_form_fields}</p>}
                                </div>

                                {/* Submit Button Text */}
                                <div>
                                    <Label htmlFor="submit_button_text">Submit Button Text</Label>
                                    <Input
                                        id="submit_button_text"
                                        value={data.submit_button_text}
                                        onChange={(e: any) => setData('submit_button_text', e.target.value)}
                                        className={errors.submit_button_text ? 'border-red-500' : ''}
                                        placeholder="E.g., Book Now, Request Assessment"
                                    />
                                    {errors.submit_button_text && <p className="text-xs text-red-500 mt-1">{errors.submit_button_text}</p>}
                                </div>
                            </div>
                        </div>


                        {/* Form Submission */}
                        <div className="flex justify-end pt-4 border-t">
                            <Button 
                                type="submit" 
                                disabled={processing} 
                                className="bg-indigo-600 hover:bg-indigo-700 transition duration-150 shadow-md hover:shadow-lg text-white"
                            >
                                <Save className="mr-2 h-4 w-4" />
                                {processing ? 'Updating...' : 'Save Changes'}
                            </Button>
                        </div>
                    </form>
                </div>
            </div>
        </AppLayout>
    );
}
