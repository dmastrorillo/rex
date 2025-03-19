import { cn } from "@/lib/utils";

export type TableToolbarProps = {
    children: React.ReactNode;
    className?: string;
};

export function TableToolbar({ children, className }: TableToolbarProps) {
    return (
        <div className={cn("border-sidebar-border/70 dark:border-sidebar-border flex items-center border-b bg-gray-50 p-4 dark:bg-gray-800", className)}>
            {children}
        </div>
    );
}

export type ToolbarItemsProps = TableToolbarProps

export function ToolbarItems({ children, className }: ToolbarItemsProps) {
    return <div className={cn("flex items-center", className)}>{children}</div>;
}