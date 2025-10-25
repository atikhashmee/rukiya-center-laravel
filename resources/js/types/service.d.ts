import { ServiceType } from '@/Enums';

export type Service = {
    id: number;
    service_name: string;
    service_type: ServiceType;
    price: string;
    start_date_and_time: string;
    end_date_and_time: string;
    description: string | null;
    duration: number | null;
    created_at: string;
    updated_at: string;
};

export type ServiceTypeEnum = {
    name: string;
    value: string;
};

export type ServiceType = 'rukiya' | 'counseling' | 'estekhara';
