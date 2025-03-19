import AppLayoutTemplate from '@/layouts/app/app-sidebar-layout';
import { type BreadcrumbItem } from '@/types';
import { usePage } from '@inertiajs/react';
import { useEffect, type ReactNode } from 'react';
import { toast, Toaster } from 'sonner';

interface AppLayoutProps {
    children: ReactNode;
    breadcrumbs?: BreadcrumbItem[];
}

type Toast = {
    content: string;
    type: 'success' | 'error' | 'info';
};

const flashMessageIsToast = (flash: unknown | Toast): flash is Toast => {
    return (flash as Toast).content !== undefined && (flash as Toast).type !== undefined;
};

const useHandleToasts = () => {
    const { flash } = usePage<{ flash: { message: Toast | unknown } }>().props;

    useEffect(() => {
        if (flash?.message && flashMessageIsToast(flash.message)) {
            const { content, type } = flash.message;
            toast[type](content);
        }
    }, [flash]);
};

export default ({ children, breadcrumbs, ...props }: AppLayoutProps) => {
    useHandleToasts();
    return (
        <AppLayoutTemplate breadcrumbs={breadcrumbs} {...props}>
            <Toaster />
            {children}
        </AppLayoutTemplate>
    );
};
