import HeadingSmall from '@/components/heading-small';
import { Badge } from '@/components/ui/badge';
import { TableToolbar, ToolbarItems } from '@/components/ui/table/table-toolbar';
import { router } from '@inertiajs/react';
import { useEffect, useState } from 'react';
import { Input } from '../ui/input';
import { CreateContactModal } from './create-contact-modal';

type ContactsSearchProps = {
    searchQuery: string;
    setSearchQuery: (searchQuery: string) => void;
};

function ContactsSearch({ searchQuery, setSearchQuery }: ContactsSearchProps) {
    useEffect(() => {
        const debounceTimeout = setTimeout(() => {
            if (searchQuery.trim()) {
                router.visit(`/contacts?searchQuery=${searchQuery}`, {
                    preserveState: true,
                    preserveScroll: true,
                });
            } else {
                router.visit(`/contacts`, {
                    preserveState: true,
                    preserveScroll: true,
                });
            }
        }, 500);

        return () => clearTimeout(debounceTimeout);
    }, [searchQuery]);
    return (
        <Input
            placeholder="Search contacts..."
            className="bg-primary placeholder:text-muted-foreground selection:bg-secondary selection:text-secondary-foreground text-secondary"
            value={searchQuery}
            onChange={(e) => setSearchQuery(e.target.value)}
        />
    );
}

type ToolbarProps = {
    amount: number;
    initialSearchQuery: string;
};

export function ContactsToolbar({ amount, initialSearchQuery }: ToolbarProps) {
    const [searchQuery, setSearchQuery] = useState<string>(initialSearchQuery);
    return (
        <TableToolbar className="justify-between gap-4">
            <ToolbarItems className="gap-2">
                <HeadingSmall title="Contacts" />
                <Badge variant="default">{amount}</Badge>
            </ToolbarItems>
            <ToolbarItems className="flex-grow">
                <ContactsSearch searchQuery={searchQuery} setSearchQuery={setSearchQuery} />
            </ToolbarItems>
            <CreateContactModal currentSearchQuery={searchQuery} />
        </TableToolbar>
    );
}
