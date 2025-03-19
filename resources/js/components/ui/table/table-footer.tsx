import { PaginatedData, PaginationLink } from '@/types';
import { Link } from '@inertiajs/react';
import { GenericTableData } from './types';

function formatPaginationLabel(label: string): string {
    return label
        .replace(/&laquo;/g, '') // Replace left-pointing double angle quotation mark
        .replace(/&raquo;/g, '') // Replace right-pointing double angle quotation mark
        .replace(/Previous/g, '←Previous') // Add left arrow for Previous
        .replace(/Next/g, 'Next→') // Add right arrow for Next
        .replace(/&nbsp;/g, ' ') // Replace non-breaking spaces
        .replace(/&#039;/g, "'") // Replace single quotes
        .replace(/&quot;/g, '"') // Replace double quotes
        .replace(/&amp;/g, '&'); // Replace ampersands
}

type PaginationButtonProps = {
    link: PaginationLink;
    preserveScroll?: boolean;
};

function PaginationButton({ link, preserveScroll }: PaginationButtonProps) {
    const label = formatPaginationLabel(link.label);

    if (!link.url) {
        return (
            <span className="cursor-not-allowed rounded bg-gray-100 px-4 py-2 text-sm text-gray-400 dark:bg-gray-700 dark:text-gray-500">
                {label}
            </span>
        );
    }

    return (
        <Link
            href={link.url}
            className={`rounded px-4 py-2 text-sm ${
                link.active
                    ? 'bg-primary text-secondary'
                    : 'bg-secondary text-gray-700 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700'
            }`}
            preserveScroll={preserveScroll}
        >
            {label}
        </Link>
    );
}

export type TableFooterProps<T extends PaginatedData<GenericTableData>> = {
    data: T;
};
export function TableFooter<T extends PaginatedData<GenericTableData>>({ data }: TableFooterProps<T>) {
    return (
        <div className="border-sidebar-border/70 dark:border-sidebar-border flex items-center justify-between border-t px-4 py-3">
            <div className="flex items-center text-sm text-gray-700 dark:text-gray-300">
                Showing <span className="mx-1 font-medium">{data.from}</span> to <span className="mx-1 font-medium">{data.to}</span> of{' '}
                <span className="mx-1 font-medium">{data.total}</span> results
            </div>
            <div className="flex space-x-1">
                {data.links.map((link, index) => (
                    <PaginationButton key={index} link={link} preserveScroll />
                ))}
            </div>
        </div>
    );
}
