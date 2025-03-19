import { ContactsToolbar } from '@/components/contacts/contacts-toolbar';
import { Table } from '@/components/ui/table';
import AppLayout from '@/layouts/app-layout';
import { PaginatedData, type BreadcrumbItem, type Contact } from '@/types';
import { Head, router } from '@inertiajs/react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Contacts',
        href: '/contacts',
    },
];

type ContactsProps = {
    contacts: PaginatedData<Contact>;
    searchQuery?: string | null;
};

export default function Contacts({ contacts, searchQuery }: ContactsProps) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Contacts" />
            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
                <div className="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex-1 overflow-hidden rounded-xl border md:min-h-min">
                    <ContactsToolbar amount={contacts.total} initialSearchQuery={searchQuery ?? ''} />
                    <Table
                        data={contacts.data}
                        columnInfo={{ id: 'ID', firstName: 'FirstName', surname: 'Surname', email: 'Email', phone: 'Phone' }}
                        paginationData={contacts}
                        onRowClick={({ id }) => router.visit(route('contacts.read', { contact: id }))}
                    />
                </div>
            </div>
        </AppLayout>
    );
}
