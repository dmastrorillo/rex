import { ContactsToolbar } from '@/components/contacts/contacts-toolbar';
import { Table } from '@/components/ui/table';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem, type Contact } from '@/types';
import { Head } from '@inertiajs/react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Contacts',
        href: '/contacts',
    },
];

type ContactsProps = {
    contacts: Contact[];
};

export default function Contacts({ contacts }: ContactsProps) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Contacts" />
            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
                <div className="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex-1 overflow-hidden rounded-xl border md:min-h-min">
                    <ContactsToolbar amount={contacts.length} />
                    <Table data={contacts} columnInfo={{ id: 'ID', firstName: 'FirstName', surname: 'Surname', email: 'Email', phone: 'Phone' }} />
                </div>
            </div>
        </AppLayout>
    );
}
