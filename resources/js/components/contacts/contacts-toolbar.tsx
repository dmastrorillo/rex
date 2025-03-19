import HeadingSmall from '@/components/heading-small';
import { Badge } from '@/components/ui/badge';
import { TableToolbar, ToolbarItems } from '@/components/ui/table/table-toolbar';
import { CreateContactModal } from './create-contact-modal';
import { ContactsSearch } from './contacts-search';

type ToolbarProps = {
    amount: number;
    initialSearchQuery: string;
};

export function ContactsToolbar({ amount, initialSearchQuery }: ToolbarProps) {
    return (
        <TableToolbar className="justify-between gap-4">
            <ToolbarItems className="gap-2">
                <HeadingSmall title="Contacts" />
                <Badge variant="default">{amount}</Badge>
            </ToolbarItems>
            <ToolbarItems className="flex-grow">
                <ContactsSearch initialSearchQuery={initialSearchQuery}  />
            </ToolbarItems>
            <CreateContactModal />
        </TableToolbar>
    );
}
