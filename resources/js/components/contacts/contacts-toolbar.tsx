import HeadingSmall from '@/components/heading-small';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { TableToolbar, ToolbarItems } from '@/components/ui/table/table-toolbar';
import { Plus } from 'lucide-react';
import { Input } from '../ui/input';

type ToolbarProps = {
    amount: number;
};

export function ContactsToolbar({ amount }: ToolbarProps) {
    return (
        <TableToolbar className="justify-between gap-4">
            <ToolbarItems className="gap-2">
                <HeadingSmall title="Contacts" />
                <Badge variant="default">{amount}</Badge>
            </ToolbarItems>
            <ToolbarItems className="flex-grow">
                <Input
                    placeholder="Search contacts..."
                    className="bg-primary placeholder:text-muted-foreground selection:bg-secondary selection:text-secondary-foreground text-secondary"
                />
            </ToolbarItems>
            <Button variant="default" onClick={() => alert('Todo')}>
                <Plus />
                <span>Create Contact</span>
            </Button>
        </TableToolbar>
    );
}
